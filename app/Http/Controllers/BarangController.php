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

    public function addToCart(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Find the product
        $barang = Barang::find($id);
        
        if (!$barang) {
            return response()->json(['error' => 'Produk tidak ditemukan!'], 404);
        }

        // Get cart from session
        $cart = session()->get('cart', []);

        // Check if product exists in cart
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $cart[$id] = [
                'barang_id' => $barang->id,
                'nama' => $barang->nama,
                'merk' => $barang->merk,  // Make sure 'merk' is included here
                'quantity' => $request->quantity,
                'harga' => $barang->harga,
                'image' => $barang->image,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart' => $cart
        ]);
    }

    public function addMultipleToCart(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:barangs,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        foreach ($request->items as $item) {
            $barang = Barang::find($item['id']);
            
            if (isset($cart[$barang->id])) {
                $cart[$barang->id]['quantity'] += $item['quantity'];
            } else {
                $cart[$barang->id] = [
                    'barang_id' => $barang->id,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'quantity' => $item['quantity'],
                    'harga' => $barang->harga,
                    'image' => $barang->image,
                ];
            }
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Semua item berhasil ditambahkan ke keranjang!',
            'cart' => $cart
        ]);
    }

    // Menampilkan isi keranjang
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return response()->json([
            'data' => $cart
        ]);
    }

    // Menghapus produk dari keranjang
    public function removeFromCart($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return response()->json();
        }

        return response()->json(['error' => 'Produk tidak ditemukan'], 404);
    }

    // Mengupdate jumlah produk di keranjang
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json();
        }

        return response()->json(['error' => 'Produk tidak ditemukan'], 404);
    }

    public function show($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        $totalQuantity = array_reduce($cart, function($sum, $item) {
            return $sum + $item['quantity'];
        }, 0);
        
        return response()->json(['count' => $totalQuantity]);
    }

}
