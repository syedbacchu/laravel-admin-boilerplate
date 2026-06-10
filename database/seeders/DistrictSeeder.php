<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('districts')->truncate();
        Schema::enableForeignKeyConstraints();

        $districts = [
            ['code' => 'DH', 'name' => 'DHAKA', 'status' => 1, 'division_code' => 'DH', 'created_at' => now()],
            ['code' => 'KU', 'name' => 'Kurigram', 'status' => 1, 'division_code' => 'KU', 'created_at' => now()],
            ['code' => 'DI', 'name' => 'Dinajpur', 'status' => 1, 'division_code' => 'DI', 'created_at' => now()],
            ['code' => 'GA', 'name' => 'Gaibandha', 'status' => 1, 'division_code' => 'GA', 'created_at' => now()],
            ['code' => 'LA', 'name' => 'Lalmonirhat', 'status' => 1, 'division_code' => 'LA', 'created_at' => now()],
            ['code' => 'NI', 'name' => 'Nilphamari', 'status' => 1, 'division_code' => 'NI', 'created_at' => now()],
            ['code' => 'PA', 'name' => 'Panchagarh', 'status' => 1, 'division_code' => 'PA', 'created_at' => now()],
            ['code' => 'TH', 'name' => 'Thakurgaon', 'status' => 1, 'division_code' => 'TH', 'created_at' => now()],
            ['code' => 'RA', 'name' => 'Rangpur', 'status' => 1, 'division_code' => 'RA', 'created_at' => now()],
            ['code' => 'HA', 'name' => 'Habiganj', 'status' => 1, 'division_code' => 'HA', 'created_at' => now()],
            ['code' => 'MO', 'name' => 'Moulvibazar', 'status' => 1, 'division_code' => 'MO', 'created_at' => now()],
            ['code' => 'SU', 'name' => 'Sunamganj', 'status' => 1, 'division_code' => 'SU', 'created_at' => now()],
            ['code' => 'SY', 'name' => 'Sylhet', 'status' => 1, 'division_code' => 'SY', 'created_at' => now()],
            ['code' => 'BA', 'name' => 'Barguna', 'status' => 1, 'division_code' => 'BA', 'created_at' => now()],
            ['code' => 'BH', 'name' => 'Bhola', 'status' => 1, 'division_code' => 'BH', 'created_at' => now()],
            ['code' => 'JH', 'name' => 'Jhalokathi', 'status' => 1, 'division_code' => 'JH', 'created_at' => now()],
            ['code' => 'PT', 'name' => 'Patuakhali', 'status' => 1, 'division_code' => 'PT', 'created_at' => now()],
            ['code' => 'PR', 'name' => 'Pirojpur', 'status' => 1, 'division_code' => 'PR', 'created_at' => now()],
            ['code' => 'BS', 'name' => 'Barishal', 'status' => 1, 'division_code' => 'BS', 'created_at' => now()],
            ['code' => 'BO', 'name' => 'Bogura', 'status' => 1, 'division_code' => 'BO', 'created_at' => now()],
            ['code' => 'CN', 'name' => 'Chapai Nawabganj', 'status' => 1, 'division_code' => 'CN', 'created_at' => now()],
            ['code' => 'JO', 'name' => 'Joypurhat', 'status' => 1, 'division_code' => 'JO', 'created_at' => now()],
            ['code' => 'NA', 'name' => 'Naogaon', 'status' => 1, 'division_code' => 'NA', 'created_at' => now()],
            ['code' => 'NT', 'name' => 'Natore', 'status' => 1, 'division_code' => 'NT', 'created_at' => now()],
            ['code' => 'PB', 'name' => 'Pabna', 'status' => 1, 'division_code' => 'PB', 'created_at' => now()],
            ['code' => 'SI', 'name' => 'Sirajganj', 'status' => 1, 'division_code' => 'SI', 'created_at' => now()],
            ['code' => 'RJ', 'name' => 'Rajshahi', 'status' => 1, 'division_code' => 'RJ', 'created_at' => now()],
            ['code' => 'CH', 'name' => 'Chuadanga', 'status' => 1, 'division_code' => 'CH', 'created_at' => now()],
            ['code' => 'JA', 'name' => 'Jashore', 'status' => 1, 'division_code' => 'JA', 'created_at' => now()],
            ['code' => 'JN', 'name' => 'Jhenaidah', 'status' => 1, 'division_code' => 'JN', 'created_at' => now()],
            ['code' => 'KS', 'name' => 'Kushtia', 'status' => 1, 'division_code' => 'KS', 'created_at' => now()],
            ['code' => 'MA', 'name' => 'Magura', 'status' => 1, 'division_code' => 'MA', 'created_at' => now()],
            ['code' => 'ME', 'name' => 'Meherpur', 'status' => 1, 'division_code' => 'ME', 'created_at' => now()],
            ['code' => 'NL', 'name' => 'Narail', 'status' => 1, 'division_code' => 'NL', 'created_at' => now()],
            ['code' => 'SK', 'name' => 'Satkhira', 'status' => 1, 'division_code' => 'SK', 'created_at' => now()],
            ['code' => 'KH', 'name' => 'Khulna', 'status' => 1, 'division_code' => 'KH', 'created_at' => now()],
            ['code' => 'BR', 'name' => 'Brahmanbaria', 'status' => 1, 'division_code' => 'BR', 'created_at' => now()],
            ['code' => 'BB', 'name' => 'Bandarban', 'status' => 1, 'division_code' => 'BB', 'created_at' => now()],
            ['code' => 'CP', 'name' => 'Chandpur', 'status' => 1, 'division_code' => 'CP', 'created_at' => now()],
            ['code' => 'CU', 'name' => 'Cumilla', 'status' => 1, 'division_code' => 'CU', 'created_at' => now()],
            ['code' => 'FE', 'name' => 'Feni', 'status' => 1, 'division_code' => 'FE', 'created_at' => now()],
            ['code' => 'KC', 'name' => 'Khagrachari', 'status' => 1, 'division_code' => 'KC', 'created_at' => now()],
            ['code' => 'LX', 'name' => 'Laxmipur', 'status' => 1, 'division_code' => 'LX', 'created_at' => now()],
            ['code' => 'NO', 'name' => 'Noakhali', 'status' => 1, 'division_code' => 'NO', 'created_at' => now()],
            ['code' => 'RM', 'name' => 'Rangamati', 'status' => 1, 'division_code' => 'RM', 'created_at' => now()],
            ['code' => 'CG', 'name' => 'Chattogram', 'status' => 1, 'division_code' => 'CG', 'created_at' => now()],
            ['code' => 'CB', 'name' => "Cox's bazar", 'status' => 1, 'division_code' => 'CB', 'created_at' => now()],
            ['code' => 'GZ', 'name' => 'Gazipur', 'status' => 1, 'division_code' => 'GZ', 'created_at' => now()],
            ['code' => 'NG', 'name' => 'Narayanganj', 'status' => 1, 'division_code' => 'NG', 'created_at' => now()],
            ['code' => 'MG', 'name' => 'Manikganj', 'status' => 1, 'division_code' => 'MG', 'created_at' => now()],
            ['code' => 'TA', 'name' => 'Tangail', 'status' => 1, 'division_code' => 'TA', 'created_at' => now()],
            ['code' => 'MP', 'name' => 'Madaripur', 'status' => 1, 'division_code' => 'MP', 'created_at' => now()],
            ['code' => 'KI', 'name' => 'Kishoreganj', 'status' => 1, 'division_code' => 'KI', 'created_at' => now()],
            ['code' => 'RB', 'name' => 'Rajbari', 'status' => 1, 'division_code' => 'RB', 'created_at' => now()],
            ['code' => 'SP', 'name' => 'Shariatpur', 'status' => 1, 'division_code' => 'SP', 'created_at' => now()],
            ['code' => 'FP', 'name' => 'Faridpur', 'status' => 1, 'division_code' => 'FP', 'created_at' => now()],
            ['code' => 'JP', 'name' => 'Jamalpur', 'status' => 1, 'division_code' => 'JP', 'created_at' => now()],
            ['code' => 'MS', 'name' => 'Mymensingh', 'status' => 1, 'division_code' => 'MS', 'created_at' => now()],
            ['code' => 'NK', 'name' => 'Netrokona', 'status' => 1, 'division_code' => 'NK', 'created_at' => now()],
            ['code' => 'SH', 'name' => 'Sherpur', 'status' => 1, 'division_code' => 'SH', 'created_at' => now()],
            ['code' => 'BG', 'name' => 'Bagerhat', 'status' => 1, 'division_code' => 'BG', 'created_at' => now()],
            ['code' => 'NS', 'name' => 'Narsingdi', 'status' => 1, 'division_code' => 'NS', 'created_at' => now()],
            ['code' => 'MU', 'name' => 'Munshiganj', 'status' => 1, 'division_code' => 'MU', 'created_at' => now()],
            ['code' => 'GP', 'name' => 'Gopalganj', 'status' => 1, 'division_code' => 'GP', 'created_at' => now()],
        ];

        foreach (array_chunk($districts, 50) as $chunk) {
            DB::table('districts')->insert($chunk);
        }
    }
}