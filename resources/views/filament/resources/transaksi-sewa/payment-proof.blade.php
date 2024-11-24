<div>
    @if($record->paymentProof)
        <div class="space-y-6">
            <!-- Transaction Info -->
            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-500">Transaction ID</p>
                    <p class="font-medium">#{{ $record->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-medium">{{ $record->status }}</p>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg p-4">
                <div class="space-y-2">
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
                    @if($showPelunasan)
                    <div>
                        <p class="text-sm text-gray-600">Pelunasan (50%)</p>
                        <p class="text-lg font-medium text-blue-600">
                            Rp {{ number_format($record->total_harga * 0.5, 0, ',', '.') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>


            <!-- New Payment Proof Section -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-medium">Bukti DP</h3>
                    <img src="{{ Storage::url($record->paymentProof->image_path) }}" 
                         alt="Payment Proof" 
                         class="mt-2 rounded-lg max-h-96">
                </div>

                @if($showPelunasan && $record->paymentProof->image_path_lunas)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium">Bukti Pelunasan</h3>
                        <img src="{{ Storage::url($record->paymentProof->image_path_lunas) }}" 
                             alt="Pelunasan Proof" 
                             class="mt-2 rounded-lg max-h-96">
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