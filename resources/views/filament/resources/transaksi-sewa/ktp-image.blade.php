
@if($record->jaminanKtp)
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
    <div class="flex justify-center">
        <img 
            src="{{ Storage::url($record->jaminanKtp->image_path) }}" 
            alt="KTP Image" 
            class="max-w-full h-auto rounded-lg shadow-lg"
        >
    </div>
@else
    <div class="text-center text-gray-500">
        Tidak ada gambar KTP
    </div>
@endif