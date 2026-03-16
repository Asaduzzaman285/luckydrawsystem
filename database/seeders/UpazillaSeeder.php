<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Upazilla;

class UpazillaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Barishal Division
            'Barguna' => ['Amtali', 'Bamna', 'Barguna Sadar', 'Betagi', 'Patharghata', 'Taltali'],
            'Barisal' => ['Agailjhara', 'Babuganj', 'Bakerganj', 'Banari para', 'Gaurnadi', 'Hizla', 'Barisal Sadar', 'Mehendiganj', 'Muladi', 'Wazirpur'],
            'Bhola' => ['Bhola Sadar', 'Burhanuddin', 'Char Fasson', 'Daulatkhan', 'Lalmohan', 'Manpura', 'Tazumuddin'],
            'Jhalokati' => ['Jhalokati Sadar', 'Kanthalia', 'Nalchity', 'Rajapur'],
            'Patuakhali' => ['Bauphal', 'Dashmina', 'Dumki', 'Galachipa', 'Kalapara', 'Mirzaganj', 'Patuakhali Sadar', 'Rangabali'],
            'Pirojpur' => ['Bhandaria', 'Kawkhali', 'Mathbaria', 'Nazirpur', 'Pirojpur Sadar', 'Nesarabad (Swarupkathi)'],

            // Chattogram Division
            'Bandarban' => ['Alikadam', 'Bandarban Sadar', 'Lama', 'Naikhongchhari', 'Rowangchhari', 'Ruma', 'Thanchi'],
            'Brahmanbaria' => ['Akhaura', 'Ashuganj', 'Bancharampur', 'Bijoynagar', 'Brahmanbaria Sadar', 'Kasba', 'Nabinagar', 'Nasirnagar', 'Sarail'],
            'Chandpur' => ['Chandpur Sadar', 'Faridganj', 'Haimchar', 'Hajiganj', 'Kachua', 'Matlab Dakshin', 'Matlab Uttar', 'Shahrasti'],
            'Chattogram' => ['Anwara', 'Banshkhali', 'Boalkhali', 'Chandanaish', 'Fatikchhari', 'Hathazari', 'Lohagara', 'Mirsharai', 'Patiya', 'Rangunia', 'Raozan', 'Sandwip', 'Satkania', 'Sitakunda'],
            'Cumilla' => ['Barura', 'Brahmanpara', 'Burichong', 'Chandina', 'Chauddagram', 'Cumilla Adarsha Sadar', 'Cumilla Dakshin Sadar', 'Daudkandi', 'Debidwar', 'Homna', 'Laksam', 'Manoharganj', 'Meghna', 'Muradnagar', 'Nangalkot', 'Nangalkot', 'Titas'],
            'Cox\'s Bazar' => ['Chakaria', 'Cox\'s Bazar Sadar', 'Kutubdia', 'Maheshkhali', 'Pekua', 'Ramu', 'Teknaf', 'Ukhia'],
            'Feni' => ['Chhagalnaiya', 'Daganbhuiyan', 'Feni Sadar', 'Parshuram', 'Sonagazi'],
            'Khagrachhari' => ['Dighinala', 'Khagrachhari Sadar', 'Lakshmichhari', 'Mahalchhari', 'Manikchhari', 'Matiranga', 'Mohalchhari', 'Panchhari', 'Ramgarh'],
            'Lakshmipur' => ['Lakshmipur Sadar', 'Raipur', 'Ramganj', 'Ramgati', 'Kamalnagar'],
            'Noakhali' => ['Begumganj', 'Chatkhil', 'Companiganj', 'Hatiya', 'Kabirhat', 'Noakhali Sadar', 'Senbagh', 'Sonaimuri', 'Subarnachar'],

            // Dhaka Division
            'Dhaka' => ['Dhamrai', 'Dohar', 'Keraniganj', 'Nawabganj', 'Savar'],
            'Faridpur' => ['Alfadanga', 'Bhanga', 'Boalmari', 'Charbhadrasan', 'Faridpur Sadar', 'Madhukhali', 'Nagarkanda', 'Sadarpur', 'Saltha'],
            'Gazipur' => ['Gazipur Sadar', 'Kaliakair', 'Kaliganj', 'Kapasia', 'Sreepur'],
            'Gopalganj' => ['Gopalganj Sadar', 'Kashiani', 'Kotalipara', 'Muksudpur', 'Tungipara'],
            'Kishoreganj' => ['Astagram', 'Bajitpur', 'Bhairab', 'Hossainpur', 'Itna', 'Karimganj', 'Katiadi', 'Kishoreganj Sadar', 'Kuliarchar', 'Mithamoin', 'Nikli', 'Pakundia', 'Tarail'],
            'Madaripur' => ['Kalkini', 'Madaripur Sadar', 'Rajoir', 'Shibchar'],
            'Manikganj' => ['Daulatpur', 'Ghior', 'Harirampur', 'Manikganj Sadar', 'Saturia', 'Shibalaya', 'Singair'],
            'Munshiganj' => ['Gazaria', 'Lohajang', 'Munshiganj Sadar', 'Sera jdikhan', 'Sirajdikhan', 'Sreenagar', 'Tongibari'],
            'Narayanganj' => ['Araihazar', 'Bandar', 'Narayanganj Sadar', 'Rupganj', 'Sonargaon'],
            'Narsingdi' => ['Belabo', 'Monohardi', 'Narsingdi Sadar', 'Palash', 'Raipura', 'Shibpur'],
            'Rajbari' => ['Baliakandi', 'Goalandaghat', 'Pangsha', 'Rajbari Sadar'],
            'Shariatpur' => ['Bhedarganj', 'Damudya', 'Gosairhat', 'Naria', 'Shariatpur Sadar', 'Zajira'],
            'Tangail' => ['Basail', 'Bhuapur', 'Delduar', 'Dhanbari', 'Ghatail', 'Gopalpur', 'Kalihati', 'Madhupur', 'Mirzapur', 'Nagarpur', 'Sakhipur', 'Tangail Sadar'],

            // Khulna Division
            'Bagerhat' => ['Bagerhat Sadar', 'Chitalmari', 'Fakirhat', 'Kachua', 'Mollahat', 'Mongla', 'Morrelganj', 'Rampal', 'Sarankhola'],
            'Chuadanga' => ['Alamdanga', 'Chuadanga Sadar', 'Damurhuda', 'Jibonnagar'],
            'Jashore' => ['Abhaynagar', 'Bagherpara', 'Chaugachha', 'Jashore Sadar', 'Jhikargachha', 'Keshabpur', 'Manirampur', 'Sharsha'],
            'Jhenaidah' => ['Harinakunda', 'Jhenaidah Sadar', 'Kaliganj', 'Kotchandpur', 'Maheshpur', 'Shailkupa'],
            'Khulna' => ['Batiaghata', 'Dacope', 'Daulatpur', 'Dumuria', 'Dighalia', 'Koyra', 'Khulna Sadar', 'Paikgachha', 'Phultala', 'Rupsa', 'Terokhada'],
            'Kushtia' => ['Bheramara', 'Daulatpur', 'Khoksa', 'Kumarkhali', 'Kushtia Sadar', 'Mirpur'],
            'Magura' => ['Magura Sadar', 'Mohammadpur', 'Sreepur', 'Shreepur'],
            'Meherpur' => ['Gangni', 'Meherpur Sadar', 'Mujibnagar'],
            'Narail' => ['Kalia', 'Lohagara', 'Narail Sadar'],
            'Satkhira' => ['Assasuni', 'Debhata', 'Kalaroa', 'Kaliganj', 'Satkhira Sadar', 'Shyamnagar', 'Tala'],

            // Mymensingh Division
            'Jamalpur' => ['Bakshiganj', 'Dewanganj', 'Islampur', 'Jamalpur Sadar', 'Madarganj', 'Melandaha', 'Sarishabari'],
            'Mymensingh' => ['Bhaluka', 'Dhobaura', 'Fulbaria', 'Gaffargaon', 'Gouripur', 'Haluaghat', 'Ishwarganj', 'Muktagachha', 'Mymensingh Sadar', 'Nandail', 'Phulpur', 'Tarakanda', 'Trishal'],
            'Netrokona' => ['Atpara', 'Barhatta', 'Durgapur', 'Kalmakanda', 'Kendua', 'Khaliajuri', 'Madan', 'Mohanganj', 'Netrokona Sadar', 'Purbadhala'],
            'Sherpur' => ['Jhenaigati', 'Nakla', 'Nalitabari', 'Sreebardi', 'Sherpur Sadar'],

            // Rajshahi Division
            'Bogura' => ['Adamdigi', 'Bogura Sadar', 'Dhunat', 'Dhupchanchia', 'Gabtali', 'Kahaloo', 'Nandigram', 'Sariakandi', 'Shajahanpur', 'Sherpur', 'Shibganj', 'Sonatola'],
            'Chapai Nawabganj' => ['Bholahat', 'Chapai Nawabganj Sadar', 'Gomostapur', 'Nachol', 'Shibganj'],
            'Joypurhat' => ['Akkelpur', 'Joypurhat Sadar', 'Kalai', 'Khetlal', 'Panchbibi'],
            'Naogaon' => ['Atrai', 'Badalgachhi', 'Dhamoirhat', 'Manda', 'Naogaon Sadar', 'Niamatpur', 'Patnitala', 'Porsha', 'Raninagar', 'Sapahar'],
            'Natore' => ['Bagatipara', 'Baraigram', 'Gurudaspur', 'Lalpur', 'Natore Sadar', 'Singra'],
            'Pabna' => ['Atgharia', 'Bera', 'Bhangura', 'Chatmohar', 'Faridpur', 'Ishwardi', 'Pabna Sadar', 'Santhia', 'Sujanagar'],
            'Rajshahi' => ['Bagha', 'Bagmara', 'Charghat', 'Durgapur', 'Godagari', 'Mohanpur', 'Paba', 'Puthia', 'Rajshahi City Corporation', 'Tanore'],  // Note: City corp not upazila, but often listed separately
            'Sirajganj' => ['Belkuchi', 'Chauhali', 'Kamarkhanda', 'Kazipur', 'Raiganj', 'Shahjadpur', 'Sirajganj Sadar', 'Sujanagar', 'Tarash', 'Ullahpara'],

            // Rangpur Division
            'Dinajpur' => ['Birampur', 'Birganj', 'Biral', 'Chirirbandar', 'Dinajpur Sadar', 'Fulbari', 'Ghoraghat', 'Hakimpur', 'Kaharol', 'Khansama', 'Nawabganj', 'Parbatipur'],
            'Gaibandha' => ['Fulchhari', 'Gaibandha Sadar', 'Gobindaganj', 'Palashbari', 'Phulchhari', 'Sadar', 'Saghata', 'Sundarganj'],
            'Kurigram' => ['Bhurungamari', 'Char Rajibpur', 'Chilmari', 'Kurigram Sadar', 'Nageshwari', 'Phulbari', 'Rajibpur', 'Raomari', 'Ulipur'],
            'Lalmonirhat' => ['Aditmari', 'Hatibandha', 'Kaliganj', 'Lalmonirhat Sadar', 'Patgram'],
            'Nilphamari' => ['Dimla', 'Domar', 'Jaldhaka', 'Kishoreganj', 'Nilphamari Sadar', 'Saidpur'],
            'Panchagarh' => ['Atwari', 'Boda', 'Debiganj', 'Panchagarh Sadar', 'Tentulia'],
            'Rangpur' => ['Badarganj', 'Gangachara', 'Kaunia', 'Mithapukur', 'Pirgachha', 'Pirganj', 'Rangpur Sadar', 'Taraganj'],
            'Thakurgaon' => ['Baliadangi', 'Haripur', 'Pirganj', 'Ranishankail', 'Thakurgaon Sadar'],

            // Sylhet Division
            'Habiganj' => ['Ajmiriganj', 'Bahubal', 'Baniachong', 'Chunarughat', 'Habiganj Sadar', 'Lakhai', 'Madhabpur', 'Nabiganj'],
            'Moulvibazar' => ['Barlekha', 'Juri', 'Kamalganj', 'Kulaura', 'Moulvibazar Sadar', 'Rajnagar', 'Sreemangal'],
            'Sunamganj' => ['Bishwambarpur', 'Chhatak', 'Derai', 'Dharampasha', 'Dowarabazar', 'Jamalganj', 'Sullah', 'Sunamganj Sadar', 'Tahirpur'],
            'Sylhet' => ['Balaganj', 'Beanibazar', 'Bishwanath', 'Companiganj', 'Fenchuganj', 'Golapganj', 'Gowainghat', 'Jaintapur', 'Kanaighat', 'Sylhet Sadar', 'Zakiganj'],
        ];

        foreach ($data as $districtName => $upazillas) {
            $district = District::where('name', $districtName)->first();

            if ($district) {
                foreach ($upazillas as $upazillaName) {
                    Upazilla::firstOrCreate([
                        'name' => $upazillaName,
                        'district_id' => $district->id,
                    ]);
                }
            } else {
                // Optional: Log missing district for debugging
                // echo "District not found: $districtName\n";
            }
        }
    }
}