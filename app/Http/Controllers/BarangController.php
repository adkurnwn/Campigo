<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        // Fetch all Barang data with media relationship
        $barang = Barang::with('media')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        // Exclude the media relationship from the JSON response
        $barang->each(function ($item) {
            $item->makeHidden('media');
        });

        // Return the data as JSON
        return response()->json($barang);
    }
}
