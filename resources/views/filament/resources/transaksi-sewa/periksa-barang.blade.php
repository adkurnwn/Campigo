<x-filament-panels::page>
    <form wire:submit.prevent="updateKondisi">
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Barang</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($record->itemsOrders as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $media = DB::table('media')
                                        ->where('model_type', 'App\Models\Barang')
                                        ->where('model_id', $item->barang->id)
                                        ->first();
                                    
                                    $imagePath = $media ? "{$media->id}/{$media->file_name}" : null;
                                @endphp
                                @if($imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         alt="{{ $item->barang->nama }}" 
                                         class="h-12 w-12 object-cover rounded">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->barang->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->barang->merk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <select wire:model.live="kondisiKembali.{{ $item->id }}" class="rounded-md border-gray-300">
                                    <option value="normal">Normal</option>
                                    <option value="rusak ringan">Rusak Ringan</option>
                                    <option value="rusak berat">Rusak Berat</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($this->hitungDenda($item->id), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-lg">Total Denda:</span>
                        <span class="text-lg text-red-600 font-bold">
                            Rp {{ number_format($this->totalDenda, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <x-filament::button 
                    type="submit"
                    color="primary">
                    Pemeriksaan Selesai
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>