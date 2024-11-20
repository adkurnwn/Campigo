
<div class="flex justify-center p-4">
    @if($getRecord()->paymentProof)
        <div class="relative w-full max-w-md">
            <img 
                src="{{ asset('storage/' . $getRecord()->paymentProof->image_path) }}" 
                alt="Payment Proof" 
                class="w-full h-auto rounded-lg shadow-lg"
            />
        </div>
    @else
        <p class="text-gray-500">No payment proof available</p>
    @endif
</div>