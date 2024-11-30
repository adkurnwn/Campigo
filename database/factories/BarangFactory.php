<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        return [
            'kode' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'nama' => $this->faker->word,
            'merk' => $this->faker->company,
            'stok' => $this->faker->numberBetween(5, 20),
            'harga' => $this->faker->numberBetween(50000, 200000),
            'berat' => $this->faker->numberBetween(1, 10),
            'deskripsi' => $this->faker->sentence,
            'kapasitas' => $this->faker->numberBetween(1, 4),
            'kategori' => $this->faker->randomElement(['tenda', 'bag', 'wears']),
            'count_disewa' => 0,
        ];
    }
}