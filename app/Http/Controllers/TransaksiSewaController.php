<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\ItemsOrder;
use Illuminate\Http\Request;
use App\Models\TransaksiSewa;
use App\Notifications\ReturnReminderEmail;
use Illuminate\Support\Facades\Auth;

class TransaksiSewaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Check for active transactions first
            if ($this->hasActiveTransaction()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak dapat melakukan penyewaan sebelum penyewaan sebelumnya selesai'
                ], 422);
            }

            // Validate request
            $validated = $request->validate([
                'payment_method' => 'required|string',
                'cart_items' => 'required|array',
                'cart_items.*.barang_id' => 'required|exists:barangs,id',
                'cart_items.*.quantity' => 'required|integer|min:1',
                'cart_items.*.price_per_day' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'total_amount' => 'required|numeric',
                'dp_amount' => 'required|numeric'
            ]);

            // Check stock availability for all items
            foreach ($validated['cart_items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                if ($barang->stok < $item['quantity']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok tidak mencukupi untuk {$barang->nama}. Stok tersedia: {$barang->stok}"
                    ], 422);
                }
            }

            // Create TransaksiSewa
            $transaksi = TransaksiSewa::create([
                'user_id' => Auth::id(),
                'tgl_pinjam' => $validated['start_date'],
                'tgl_kembali' => $validated['end_date'],
                'total_harga' => $validated['total_amount'],
                'dp_amount' => $validated['dp_amount'],
                'metode_bayar' => $validated['payment_method'],
                'status' => 'belum bayar'
            ]);

            // Create ItemsOrder for each item and decrease stock
            foreach ($validated['cart_items'] as $item) {
                ItemsOrder::create([
                    'transaksi_sewa_id' => $transaksi->id,
                    'barang_id' => $item['barang_id'],
                    'quantity' => $item['quantity'],
                    'price_per_day' => $item['price_per_day'],
                    'subtotal' => $item['price_per_day'] * $item['quantity']
                ]);

                // Decrease stock
                $barang = Barang::findOrFail($item['barang_id']);
                $barang->decrement('stok', $item['quantity']);
            }

            // Clear cart
            session()->forget('cart');

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => [
                    'transaction_id' => $transaksi->id,
                    'total_amount' => $validated['total_amount'],
                    'dp_amount' => $validated['dp_amount']
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TransaksiSewa $transaksiSewa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransaksiSewa $transaksiSewa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiSewa $transaksiSewa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiSewa $transaksiSewa)
    {
        //
    }

    public function checkoutPesanan(Request $request)
    {
        
    }

    public function getUserTransactions()
    {
        try {
            
            // Add reminder check here
            $this->sendReturnReminders();
            
            // Cancel expired transactions and update penalties
            $this->cancelExpiredTransactions();
            $this->updateLatePenalties();

            // Rest of the method remains the same
            $transactions = TransaksiSewa::with(['itemsOrders.barang', 'user'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'tgl_pinjam' => $transaction->tgl_pinjam,
                        'tgl_kembali' => $transaction->tgl_kembali,
                        'total_harga' => $transaction->total_harga,
                        'dp_amount' => $transaction->dp_amount,
                        'status' => $transaction->status,
                        'payment_method' => $transaction->payment_method,
                        'total_denda' => $transaction->total_denda,
                        'items' => $transaction->itemsOrders->map(function ($item) {
                            return [
                                'barang_id' => $item->barang->id,
                                'name' => $item->barang->nama,
                                'merk' => $item->barang->merk,
                                'image' => $item->barang->image,
                                'quantity' => $item->quantity,
                                'price_per_day' => $item->price_per_day,
                                'subtotal' => $item->subtotal,
                                'denda' => $item->denda,
                                'kondisi_kembali' => $item->kondisi_kembali,
                            ];
                        })
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching transactions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $transaction = TransaksiSewa::findOrFail($id);
            
            // Ensure the user owns this transaction
            if ($transaction->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 403);
            }

            $availableStatuses = [
                'belum bayar',
                'pending',
                'pembayaran terkonfirmasi',
                'berlangsung',
                'diperiksa',
                'selesai',
                'dibatalkan'
            ];

            $transaction->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating transaction status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hasActiveTransaction()
    {
        return TransaksiSewa::where('user_id', Auth::id())
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->exists();
    }

    private function cancelExpiredTransactions()
    {
        TransaksiSewa::expiredUnpaid()->update(['status' => 'dibatalkan']);
    }

    private function updateLatePenalties()
    {
        // Get transactions that should be cancelled before updating them
        $transactionsToCancel = TransaksiSewa::shouldBeCancelled()->with('itemsOrders.barang')->get();
        
        foreach ($transactionsToCancel as $transaction) {
            // Update the transaction status
            $transaction->update(['status' => 'dibatalkan']);
            
            // Return items to stock
            foreach ($transaction->itemsOrders as $itemOrder) {
                $barang = $itemOrder->barang;
                $barang->increment('stok', $itemOrder->quantity);
            }
        }

        // Update penalties for late returns
        $lateTransactions = TransaksiSewa::lateReturns()->get();
        foreach ($lateTransactions as $transaction) {
            $penalty = $transaction->calculateLatePenalty();
            $transaction->update(['total_denda' => $penalty]);
        }
    }

    private function sendReturnReminders()
    {
        $dueSoonTransactions = TransaksiSewa::dueSoon()->with(['user', 'itemsOrders.barang'])->get();
        
        foreach ($dueSoonTransactions as $transaction) {
            $transaction->user->notify(new ReturnReminderEmail($transaction));
            $transaction->update(['reminded' => true]);
        }
    }

    
}
