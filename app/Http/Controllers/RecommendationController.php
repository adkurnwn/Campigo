<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function getRecommendations(Request $request)
    {
        $request->validate([
            'jumlahOrang' => 'required|integer|min:1',
            'jenisCamping' => 'required|string',
            'durasi' => 'required|integer|min:1'
        ]);

        $remainingPeople = $request->jumlahOrang;
        $recommendations = [];
        
        $query = Barang::where('kategori', 'Tenda')
            ->where('stok', '>', 0);
            
        if ($request->jenisCamping !== 'perkemahan') {
            $query->whereNotIn('kapasitas', [20, 30, 50]);
        }
        
        // Group tents by capacity and collect all models for each capacity
        $availableTents = $query->get()->groupBy('kapasitas');
        
        // Sort by capacity in descending order
        $availableTents = $availableTents->sortKeysDesc();

        while ($remainingPeople > 0 && count($availableTents) > 0) {
            $bestCombination = $this->findBestTentCombination($availableTents, $remainingPeople);
            
            if (empty($bestCombination)) {
                // If no optimal combination found, use the largest available capacity
                $largestCapacity = $availableTents->keys()->first();
                $tentsWithCapacity = $availableTents[$largestCapacity];
                
                // Calculate how many tents we need of this capacity
                $totalNeeded = ceil($remainingPeople / $largestCapacity);
                $remainingNeeded = $totalNeeded;
                $tendsToAdd = [];

                // Try to fulfill the need using multiple tent models of same capacity
                foreach ($tentsWithCapacity as $tent) {
                    if ($remainingNeeded <= 0) break;
                    
                    $quantityFromThisTent = min($tent->stok, $remainingNeeded);
                    $tendsToAdd[] = [
                        'id' => $tent->id,
                        'nama' => $tent->nama,
                        'kapasitas' => $tent->kapasitas,
                        'quantity' => $quantityFromThisTent,
                        'total_capacity' => $tent->kapasitas * $quantityFromThisTent
                    ];
                    
                    $remainingNeeded -= $quantityFromThisTent;
                }

                // Add all tents of this capacity to recommendations
                $recommendations = array_merge($recommendations, $tendsToAdd);
                $totalCapacityAdded = array_sum(array_column($tendsToAdd, 'total_capacity'));
                $remainingPeople -= $totalCapacityAdded;

                // Remove this capacity if all tents are fully utilized
                $totalStockRemaining = $tentsWithCapacity->sum('stok') - array_sum(array_column($tendsToAdd, 'quantity'));
                if ($totalStockRemaining <= 0) {
                    $availableTents->forget($largestCapacity);
                }
            } else {
                foreach ($bestCombination as $tentId => $quantity) {
                    $tent = Barang::find($tentId);
                    $recommendations[] = [
                        'id' => $tent->id,
                        'nama' => $tent->nama,
                        'kapasitas' => $tent->kapasitas,
                        'quantity' => $quantity,
                        'total_capacity' => $tent->kapasitas * $quantity
                    ];
                    $remainingPeople -= ($tent->kapasitas * $quantity);
                    
                    // Update available tents
                    if ($tent->stok <= $quantity) {
                        $availableTents->forget($tent->kapasitas);
                    }
                }
            }
        }
        
        return response()->json([
            'recommendations' => $recommendations,
            'totalPeople' => $request->jumlahOrang,
            'accommodated' => $request->jumlahOrang - $remainingPeople,
            'remainingPeople' => max(0, $remainingPeople)
        ]);
    }

    private function findBestTentCombination($availableTents, $targetPeople)
    {
        $bestCombination = [];
        $bestScore = PHP_INT_MAX;
        
        // Try same capacity combinations first
        foreach ($availableTents as $capacity => $tents) {
            if ($capacity > $targetPeople) continue;
            
            $totalStockOfCapacity = $tents->sum('stok');
            $neededQuantity = ceil($targetPeople / $capacity);
            
            if ($totalStockOfCapacity >= $neededQuantity) {
                $waste = ($capacity * $neededQuantity) - $targetPeople;
                $score = $waste * 2; // Prioritize exact fits
                
                if ($score < $bestScore) {
                    $bestScore = $score;
                    $bestCombination = $this->distributeQuantityAcrossTents($tents, $neededQuantity);
                }
            }
        }

        // If no single capacity solution found, try mixed capacities
        if (empty($bestCombination)) {
            $capacities = array_keys($availableTents->toArray());
        
            // Helper function to calculate distribution score
            $calculateScore = function($totalCapacity, $capacityVariance, $waste) {
                // Penalize waste more heavily than capacity variance
                return ($waste * 2) + $capacityVariance;
            };

            // Try combinations of up to 2 different tent sizes
            foreach ($capacities as $i => $cap1) {
                $tent1 = $availableTents[$cap1]->first();
                
                // Try single tent type first
                if ($tent1->kapasitas <= $targetPeople) {
                    $qty1 = min(ceil($targetPeople / $tent1->kapasitas), $tent1->stok);
                    $totalCapacity = $tent1->kapasitas * $qty1;
                    $waste = $totalCapacity - $targetPeople;
                    
                    // For single tent type, variance is 0
                    $score = $calculateScore($totalCapacity, 0, $waste);
                    
                    if ($waste >= 0 && $score < $bestScore) {
                        $bestScore = $score;
                        $bestCombination = [$tent1->id => $qty1];
                    }
                }
                
                // Try combinations with another tent size
                for ($j = $i + 1; $j < count($capacities); $j++) {
                    $cap2 = $capacities[$j];
                    $tent2 = $availableTents[$cap2]->first();
                    
                    // Calculate the ideal split ratio
                    $idealPeoplePerTent = $targetPeople / 2;
                    
                    // Find quantities that best match the ideal split
                    for ($qty1 = 1; $qty1 <= $tent1->stok; $qty1++) {
                        for ($qty2 = 1; $qty2 <= $tent2->stok; $qty2++) {
                            $cap1Total = $tent1->kapasitas * $qty1;
                            $cap2Total = $tent2->kapasitas * $qty2;
                            $totalCapacity = $cap1Total + $cap2Total;
                            $waste = $totalCapacity - $targetPeople;
                            
                            if ($waste >= 0) {
                                // Calculate how evenly distributed the capacity is
                                $avgCapPerTent = $totalCapacity / ($qty1 + $qty2);
                                $variance = abs($cap1Total/$qty1 - $avgCapPerTent) + 
                                          abs($cap2Total/$qty2 - $avgCapPerTent);
                                
                                $score = $calculateScore($totalCapacity, $variance, $waste);
                                
                                if ($score < $bestScore) {
                                    $bestScore = $score;
                                    $bestCombination = [
                                        $tent1->id => $qty1,
                                        $tent2->id => $qty2
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $bestCombination;
    }

    private function distributeQuantityAcrossTents($tents, $totalNeeded)
    {
        $distribution = [];
        $remainingNeeded = $totalNeeded;

        foreach ($tents as $tent) {
            if ($remainingNeeded <= 0) break;
            
            $quantityFromThisTent = min($tent->stok, $remainingNeeded);
            if ($quantityFromThisTent > 0) {
                $distribution[$tent->id] = $quantityFromThisTent;
                $remainingNeeded -= $quantityFromThisTent;
            }
        }

        return $distribution;
    }
}