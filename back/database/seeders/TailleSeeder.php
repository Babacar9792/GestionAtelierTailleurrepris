<?php

namespace Database\Seeders;

use App\Models\Taille;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TailleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tailles = [
           [ "taille" => 'XL'],
           [ "taille" => '2XL'],
           [ "taille" => 'M'],
           [ "taille" => 'S'],
           [ "taille" => 'L']
        ];

        Taille::created($tailles);
    }
}
