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
                </div>
            </div>

            <!-- Payment Proof Image -->
            <div class="rounded-lg overflow-hidden border border-gray-200">
                <img 
                    src="{{ asset('storage/' . $record->paymentProof->image_path) }}" 
                    alt="Payment Proof"
                    class="w-full h-auto"
                >
            </div>

            <!-- Action Buttons -->
            @if($record->status === 'pending')
            <div class="flex gap-3">
                <button
                    x-on:click="$wire.updateStatus({{ $record->id }}, 'pembayaran terkonfirmasi')"
                    class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                    Terima
                </button>
                <button
                    x-on:click="$wire.updateStatus({{ $record->id }}, 'dibatalkan')"
                    class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Tolak
                </button>
            </div>
            @endif
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