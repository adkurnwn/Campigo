
@if($record->jaminanKtp)
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