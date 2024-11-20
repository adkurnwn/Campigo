<?php

namespace App\Http\Controllers;

use App\Models\JaminanKtp;
use App\Models\TransaksiSewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JaminanKtpController extends Controller
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
            'ktp_image' => 'required|string', // Base64 image string
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

            // Convert base64 to image and save
            $image_parts = explode(";base64,", $request->ktp_image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'ktp_' . Str::random(10) . '_' . time() . '.png';
            
            // Store the file
            $path = 'ktp-images/' . $fileName;
            Storage::disk('public')->put($path, $image_base64);

            // Create jaminan KTP record
            $jaminanKtp = JaminanKtp::create([
                'transaksi_sewa_id' => $request->transaction_id,
                'image_path' => $path
            ]);

            return response()->json([
                'message' => 'KTP berhasil disimpan',
                'data' => $jaminanKtp
            ], 201);

        } catch (\Exception $e) {
            // Delete uploaded file if it exists
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'message' => 'Gagal menyimpan KTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JaminanKtp $jaminanKtp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JaminanKtp $jaminanKtp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JaminanKtp $jaminanKtp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JaminanKtp $jaminanKtp)
    {
        //
    }
}
