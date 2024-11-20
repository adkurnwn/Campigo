<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use App\Models\TransaksiSewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentProofController extends Controller
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
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'transaction_id' => 'required|exists:transaksi_sewas,id'
        ]);

        try {
            // Get the transaction
            $transaction = TransaksiSewa::findOrFail($request->transaction_id);

            // Check if user owns this transaction
            if ($transaction->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Generate unique filename
            $fileName = 'payment_proof_' . Str::random(10) . '_' . time() . '.' . $request->payment_proof->extension();
            
            // Store the file
            $path = $request->file('payment_proof')->storeAs(
                'payment-proofs',
                $fileName,
                'public'
            );

            // Create payment proof record
            $paymentProof = PaymentProof::create([
                'transaksi_sewa_id' => $request->transaction_id,
                'image_path' => $path
            ]);

            // Update transaction status
            $transaction->update([
                'status' => 'pending'
            ]);

            return response()->json([
                'message' => 'Bukti pembayaran berhasil diunggah',
                'data' => $paymentProof
            ], 201);

        } catch (\Exception $e) {
            // Delete uploaded file if it exists
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'message' => 'Gagal mengunggah bukti pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentProof $paymentProof)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentProof $paymentProof)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentProof $paymentProof)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentProof $paymentProof)
    {
        //
    }
}
