<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemsOrder;
use Illuminate\Http\Request;
use App\Models\TransaksiSewa;
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

            // Create TransaksiSewa
            $transaksi = TransaksiSewa::create([
                'user_id' => Auth::id(),
                'tgl_pinjam' => $validated['start_date'],
                'tgl_kembali' => $validated['end_date'],
                'total_harga' => $validated['total_amount'],
                'dp_amount' => $validated['dp_amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending'
            ]);

            // Create ItemsOrder for each item
            foreach ($validated['cart_items'] as $item) {
                ItemsOrder::create([
                    'transaksi_sewa_id' => $transaksi->id,
                    'barang_id' => $item['barang_id'],
                    'quantity' => $item['quantity'],
                    'price_per_day' => $item['price_per_day'],
                    'subtotal' => $item['price_per_day'] * $item['quantity']
                ]);
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
            $transactions = TransaksiSewa::with(['itemsOrders.barang', 'pelanggan'])
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
                        'items' => $transaction->itemsOrders->map(function ($item) {
                            return [
                                'barang_id' => $item->barang->id,
                                'name' => $item->barang->nama,
                                'merk' => $item->barang->merk,
                                'image' => $item->barang->image,
                                'quantity' => $item->quantity,
                                'price_per_day' => $item->price_per_day,
                                'subtotal' => $item->subtotal
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
}
