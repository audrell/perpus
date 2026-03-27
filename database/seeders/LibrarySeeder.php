<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'audrel.transafe@gmail.com',
                'password' => Hash::make('12345'),
                'role' => 'admin',
            ],
            [
                'name' => 'Anggota Biasa',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
        ]);

        $categories = ['Fiksi', 'Sains', 'Teknologi', 'Sejarah', 'Biologi'];
        foreach ($categories as $cat) {
            $catId = DB::table('categories')->InsertGetId([
                'name' => $cat,
                'slug' => Str::slug($cat),
            ]);

            for ($i = 1; $i <= 5; $i++) {
                $stok = rand(5, 20);

                DB::table('books')->insert([
                    'category_id' => $catId,
                    'title' => $faker->sentence(3),
                    'author' => $faker->name,
                    'publisher' => $faker->company,
                    'isbn' => $faker->isbn13,
                    'stock' => rand(1, 10),
                    'year' => $faker->year(),
                    'rack_location' => 'Rak ' . $faker->randomElement(['A1', 'A2', 'B1', 'B2', 'C1']),
                    'quantity_total' => $stok,
                    'quantity_available' => $stok,
                    'stock' => $stok,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
