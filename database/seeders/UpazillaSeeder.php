<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Upazilla;

class UpazillaSeeder extends Seeder
{
    public function run(): void
    {
        // Example mapping for common districts. 
        // You can expand this list with more data as needed.
        $data = [
            'Dhaka' => ['Dhamrai', 'Dohar', 'Keraniganj', 'Nawabganj', 'Savar'],
            'Chattogram' => ['Anwara', 'Banshkhali', 'Boalkhali', 'Chandanaish', 'Fatikchhari', 'Hathazari', 'Lohagara', 'Mirsharai', 'Patiya', 'Rangunia', 'Raozan', 'Sandwip', 'Satkania', 'Sitakunda'],
            'Gazipur' => ['Gazipur Sadar', 'Kaliakair', 'Kaliganj', 'Kapasia', 'Sreepur'],
            'Narayanganj' => ['Araihazar', 'Bandar', 'Narayanganj Sadar', 'Rupganj', 'Sonargaon'],
            // Add more districts and their upazillas here...
        ];

        foreach ($data as $districtName => $upazillas) {
            $district = District::where('name', $districtName)->first();
            
            if ($district) {
                foreach ($upazillas as $upazillaName) {
                    Upazilla::firstOrCreate([
                        'name' => $upazillaName,
                        'district_id' => $district->id
                    ]);
                }
            }
        }
    }
}
