<div>
    @if ($record->paymentProof)
        <div class="space-y-6">
            <!-- Transaction Info -->
            <div class="flex gap-8 py-4 px-6 bg-gray-200 rounded-lg justify-between">
                <div>
                    <div>
                        <p class="text-sm text-gray-500">Transaction ID</p>
                        <p class="font-medium">#{{ $record->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="font-medium">{{ $record->status }}</p>
                    </div>
                </div>
                <div>
                    <div>
                        <p class="text-sm text-gray-500">Nama Customer</p>
                        <p class="font-medium">{{ $record->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $record->user->email }}</p>
                    </div>
                </div>
                <div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                        <p class="font-medium">{{ $record->tgl_pinjam }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kembali</p>
                        <p class="font-medium">{{ $record->tgl_kembali }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment Information -->
        <div class=" p-4">
            <div class="space-y-2">
                <div class="flex justify-between">
                    <div>
                        <div>
                            <p class="text-sm text-gray-600">Total Harga</p>
                            <p class="text-xl font-semibold text-gray-900">
                                Rp {{ number_format($record->total_harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">DP (50%)</p>
                            <p class="text-lg font-medium text-teal-600">
                                Rp {{ number_format($record->total_harga * 0.5, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>
                    <div>
                        <div>
                            <p class="text-sm text-gray-600">Total Denda</p>
                            <p class="text-lg font-medium text-red-600">
                                Rp {{ number_format($record->total_denda, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Keseluruhan</p>
                            <p class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($record->total_harga + $record->total_denda, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-600">

                </div>
                <div class="flex justify-end pt-3">
                    <div>
                        <p class="text-sm text-gray-600">Sisa Pelunasan</p>
                        <p class="text-xl font-medium text-blue-600">
                            Rp
                            {{ number_format($record->total_harga * 0.5 + ($record->total_denda ?? 0), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                <div class="border-b border-gray-600">

                </div>

            </div>
        </div>


        <!-- New Payment Proof Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2 bg-gray-200 rounded-lg px-2 py-2">
                <h3 class="text-lg font-medium">Bukti DP</h3>
                <div class="aspect-w-16 aspect-h-9">
                    <img src="{{ Storage::url($record->paymentProof->image_path) }}" alt="Payment Proof"
                        class="rounded-lg object-cover w-full h-full">
                </div>
            </div>

            @if ($showPelunasan && $record->paymentProof->image_path_lunas)
                <div class="space-y-2 bg-gray-200 rounded-lg px-2 py-2">
                    <h3 class="text-lg font-medium">Bukti Pelunasan</h3>
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ Storage::url($record->paymentProof->image_path_lunas) }}" alt="Pelunasan Proof"
                            class="rounded-lg object-cover w-full h-full">
                    </div>
                </div>
            @endif
        </div>


</div>
@else
<div class="text-gray-500">No payment proof available</div>
@endif
</div>

@script
    <script>
        $wire.updateStatus = async (id, status) => {
            await $wire.call('updateTransactionStatus', id, status);
            // Close modal after update
            $wire.dispatch('close-modal');
        }
    </script>
@endscript
