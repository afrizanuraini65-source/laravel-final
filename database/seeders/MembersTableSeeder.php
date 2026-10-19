<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MembersTableSeeder extends Seeder
{
    public function run(): void
    {
        $period = OrganizationPeriod::where('is_active', true)->first();
        if (! $period) {
            $period = OrganizationPeriod::firstOrCreate([
                'name' => '2025/2026',
            ], [
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_active' => true,
            ]);
        }

        $members = [
            ['name' => 'Aora Sabrina Maylani', 'kelas' => 'X AKL 1'],
            ['name' => 'Khusnun Khamida', 'kelas' => 'X AKL 1'],
            ['name' => 'Nezya Laelyza Azizah', 'kelas' => 'X AKL 1'],
            ['name' => 'Amanda Nur Aulia', 'kelas' => 'X AKL 3'],
            ['name' => 'Arsaila Hanaa Yumika', 'kelas' => 'X AKL 3'],
            ['name' => 'Chary Ana Lutfiah', 'kelas' => 'X AKL 3'],
            ['name' => 'Flora Suci Rahmadani', 'kelas' => 'X AKL 3'],
            ['name' => 'Ghina Ramadhani Nur Irawan', 'kelas' => 'X AKL 3'],
            ['name' => 'Lady Kirana Sezara', 'kelas' => 'X AKL 3'],
            ['name' => 'Saskia Zahra Zhaeni', 'kelas' => 'X AKL 3'],
            ['name' => 'Praba Dea Sabrina', 'kelas' => 'X AKL 3'],
            ['name' => 'Emilia Kencana Nursyfana', 'kelas' => 'X AKL 4'],
            ['name' => 'Putri Dwi Artika', 'kelas' => 'X AKL 4'],
            ['name' => 'Shofiyah Rahma Faustina', 'kelas' => 'X AKL 4'],
            ['name' => 'Hafizah Nada Santosa', 'kelas' => 'X AKL 5'],
            ['name' => 'Hana Yumna Oktavia', 'kelas' => 'X AKL 5'],
            ['name' => 'Naysila Tri Larasati', 'kelas' => 'X AKL 5'],
            ['name' => 'Rahima Nurmila Safa', 'kelas' => 'X AKL 5'],
            ['name' => 'Charissa Syifa Avrillia Putri', 'kelas' => 'X AKL 5'],
            ['name' => 'Rahma Nurmila Safa', 'kelas' => 'X AKL 5'],
            ['name' => 'Shafira Wahyu Destyan', 'kelas' => 'X AKL 5'],
            ['name' => 'Selvi Satya Anjany', 'kelas' => 'X AKL 5'],
            ['name' => 'Sheiya Vanoby Elan', 'kelas' => 'X AKL 5'],
            ['name' => 'Dhiya Zahirah Putri', 'kelas' => 'X DKV 1'],
            ['name' => 'Kanaya Shyfa Putri Wibowo', 'kelas' => 'X DKV 1'],
            ['name' => 'Amelia Bunga Athamevia', 'kelas' => 'X DKV 2'],
            ['name' => 'Janeeta Florin Prabowo', 'kelas' => 'X DKV 2'],
            ['name' => 'Okti Tri Wahyuni', 'kelas' => 'X DKV 2'],
            ['name' => 'Renata Sallyana', 'kelas' => 'X DKV 2'],
            ['name' => 'Safayaz Husna Qotrunna', 'kelas' => 'X DKV 2'],
            ['name' => 'Salsabil Naafilah', 'kelas' => 'X DKV 2'],
            ['name' => 'Suci Ramadhani', 'kelas' => 'X DKV 2'],
            ['name' => 'Aizyah Khumaina', 'kelas' => 'X MPLB 1'],
            ['name' => 'Anggun Karunia', 'kelas' => 'X MPLB 1'],
            ['name' => 'Apriliana Hikmah Putri', 'kelas' => 'X MPLB 1'],
            ['name' => 'Ayyuna Khairunnisa', 'kelas' => 'X MPLB 1'],
            ['name' => 'Firelsia An Nafi', 'kelas' => 'X MPLB 1'],
            ['name' => 'Laelatus Solikhah', 'kelas' => 'X MPLB 1'],
            ['name' => 'Rizka Cinta Amalia', 'kelas' => 'X MPLB 1'],
            ['name' => 'Rizki Refi Anissa', 'kelas' => 'X MPLB 1'],
            ['name' => 'Syafa Nurdiani', 'kelas' => 'X MPLB 1'],
            ['name' => 'Alya Dwi Paramita', 'kelas' => 'X MPLB 2'],
            ['name' => 'Karima Tsabita', 'kelas' => 'X MPLB 2'],
            ['name' => 'Keeva Abira Sekar Pranci', 'kelas' => 'X MPLB 2'],
            ['name' => 'Rizti Refi Anissa', 'kelas' => 'X MPLB 2'],
            ['name' => 'Zakiah Farah Hani', 'kelas' => 'X MPLB 2'],
            ['name' => 'Dhurriyatus Sholihah', 'kelas' => 'X MPLB 3'],
            ['name' => 'Fatimah Zahra', 'kelas' => 'X MPLB 3'],
            ['name' => 'Felicia Anindita Afrian', 'kelas' => 'X MPLB 3'],
            ['name' => 'Meysi Fadila P', 'kelas' => 'X MPLB 3'],
            ['name' => 'Nisa Nur Fadilah', 'kelas' => 'X MPLB 3'],
            ['name' => 'Adelia Ayu Putri', 'kelas' => 'X PM 1'],
            ['name' => 'Al Zilma Mulia Nurja', 'kelas' => 'X PM 1'],
            ['name' => 'Arinda Luthfianti', 'kelas' => 'X PM 1'],
            ['name' => 'Ema Aulia Putri', 'kelas' => 'X PM 1'],
            ['name' => 'Gwendy Aulia Rahmat', 'kelas' => 'X PM 1'],
            ['name' => 'Nurul Wahyuni', 'kelas' => 'X PM 1'],
            ['name' => 'Revina Setya Anggraeni', 'kelas' => 'X PM 1'],
            ['name' => 'Claudya Estriyaningrum', 'kelas' => 'X PM 2'],
            ['name' => 'Hilmi Putera Santosa', 'kelas' => 'X PM 2'],
            ['name' => 'Velly Silviana Hermanto', 'kelas' => 'X PM 2'],
            ['name' => 'Annisa Mir\'atul Husna', 'kelas' => 'X PM 3'],
            ['name' => 'Adinda Cindy Bella Cesa', 'kelas' => 'X PM 3'],
            ['name' => 'Agista Dwi Rahmadhni', 'kelas' => 'X PM 3'],
            ['name' => 'Freeya Lituhayu', 'kelas' => 'X PM 3'],
            ['name' => 'Maulida Marhamah', 'kelas' => 'X PM 3'],
            ['name' => 'Chairunnisa Niatus S.', 'kelas' => 'X PPLG 2'],
            ['name' => 'Faizah Zidny Maulida', 'kelas' => 'X PPLG 2'],
            ['name' => 'Naura Putri Elysia', 'kelas' => 'X PPLG 2'],
            ['name' => 'Salfa Nur Stevia', 'kelas' => 'X PPLG 2'],
            ['name' => 'Velisa Nada Setiawan', 'kelas' => 'X PPLG 2'],
            ['name' => 'Dias Felix Zuhayr', 'kelas' => 'X PPLG 3'],
            ['name' => 'Prajna Auliya Putri Yulia', 'kelas' => 'X PPLG 3'],
            ['name' => 'Ashila Qonita Wijanto', 'kelas' => 'X TF 1'],
            ['name' => 'Devika Zakiahnaf Ha', 'kelas' => 'X TF 1'],
            ['name' => 'Dhania Rahma Putri', 'kelas' => 'X TF 1'],
            ['name' => 'Dhiya Lunetta Ramadani', 'kelas' => 'X TF 1'],
            ['name' => 'Fayola Nathania Ramadhani', 'kelas' => 'X TF 1'],
            ['name' => 'Karin Raihana Putri', 'kelas' => 'X TF 1'],
            ['name' => 'Sery Amde', 'kelas' => 'X TF 1'],
            ['name' => 'Aisyah Khoiriyatul Lailiyah', 'kelas' => 'X TF 2'],
            ['name' => 'Nabila Azzahra', 'kelas' => 'X TF 2'],
            ['name' => 'Ainsya Kayla Hafsa', 'kelas' => 'X TJKT 1'],
            ['name' => 'Luqman Hidayat', 'kelas' => 'X TJKT 1'],
            ['name' => 'Alita Ghemas', 'kelas' => 'XI AKL 1'],
            ['name' => 'Anindya Faradi Inaya', 'kelas' => 'XI AKL 1'],
            ['name' => 'Dinda Aira Sazza', 'kelas' => 'XI AKL 1'],
            ['name' => 'Dwi Wulan Yunitasari', 'kelas' => 'XI AKL 1'],
            ['name' => 'Fanisha Isnaeni R', 'kelas' => 'XI AKL 1'],
            ['name' => 'Maghiezta Aitha Funnisa', 'kelas' => 'XI AKL 1'],
            ['name' => 'Nanda Azellia Berliana', 'kelas' => 'XI AKL 1'],
            ['name' => 'Zada Nurmala Tsaqif', 'kelas' => 'XI AKL 1'],
            ['name' => 'Zinniroh Al Ashwani', 'kelas' => 'XI AKL 1'],
            ['name' => 'Asyifa Khoerunnisa', 'kelas' => 'XI AKL 2'],
            ['name' => 'Hafizah Safa Mangali', 'kelas' => 'XI AKL 2'],
            ['name' => 'Nayla Amalia Khairunnisa', 'kelas' => 'XI AKL 2'],
            ['name' => 'Nur Ngaisatuzzahro', 'kelas' => 'XI AKL 2'],
            ['name' => 'Nurlita Dwi Khasanah', 'kelas' => 'XI AKL 2'],
            ['name' => 'Siti Raudhah Chong', 'kelas' => 'XI AKL 2'],
            ['name' => 'Vania Nur Aida Fitri', 'kelas' => 'XI AKL 2'],
            ['name' => 'Widad Kamilah Putri', 'kelas' => 'XI AKL 2'],
            ['name' => 'Shofyah Rahma Faustina', 'kelas' => 'XI AKL 4'],
            ['name' => 'Armiya All Khusna', 'kelas' => 'XI AKL 5'],
            ['name' => 'Keisya Anjani', 'kelas' => 'XI AKL 5'],
            ['name' => 'Liza Lu’ai’atinniswah', 'kelas' => 'XI AKL 5'],
            ['name' => 'Salsabila', 'kelas' => 'XI AKL 5'],
            ['name' => 'Syafril Fi Arrasyi', 'kelas' => 'XI AKL 5'],
            ['name' => 'Tifa Lailatul Ibada', 'kelas' => 'XI AKL 5'],
            ['name' => 'Vierna Damayanti', 'kelas' => 'XI AKL 5'],
            ['name' => 'Athaya Calysta Putri', 'kelas' => 'XI DKV 1'],
            ['name' => 'Causarina Ayunindya P', 'kelas' => 'XI DKV 1'],
            ['name' => 'Jihan Eka Putri', 'kelas' => 'XI DKV 1'],
            ['name' => 'Abella Nasya Dwi Anindita', 'kelas' => 'XI DKV 2'],
            ['name' => 'Aira Neza Karunia Putri', 'kelas' => 'XI DKV 2'],
            ['name' => 'Amrina Muthmainah', 'kelas' => 'XI DKV 2'],
            ['name' => 'Aura Putri Syabani', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Asyifa Zara Sofi', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Cinta Intan Nuraini', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Devita Istiqomah', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Fadhilatu Sholeha', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Indah Dwi Alysa Putri', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Nabilah Yusri Maheswari', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Naila Ataillah Ain', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Nalaya Hayuningtyas', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Rena Aulia Saputri', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Robi Suhestamay', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Salisatul Fadilah', 'kelas' => 'XI MPLB 1'],
            ['name' => 'Anindya Rachel', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Devi Laylika Fatrilah', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Elina Sesyahutami', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Maruli Dwi Aretha', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Maylinna Nur Cahyani', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Nazmi Aulia', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Nurul Zahro', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Revalina Dwi Saputri', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Ulfatun Khasanah', 'kelas' => 'XI MPLB 2'],
            ['name' => 'Aulia Zahra Ainun Riza', 'kelas' => 'XI PM 1'],
            ['name' => 'Intan Afifah', 'kelas' => 'XI PM 1'],
            ['name' => 'Ririn Lestari', 'kelas' => 'XI PM 1'],
            ['name' => 'Habibah Khofifatun Annisa', 'kelas' => 'XI PM 2'],
            ['name' => 'Livia Putri Az-Zahra', 'kelas' => 'XI PM 2'],
            ['name' => 'Rasya Dwi Yuliani', 'kelas' => 'XI PM 2'],
            ['name' => 'Amanah Sabrina Putri', 'kelas' => 'XI PM 3'],
            ['name' => 'Annisa Jihan Nugraheni', 'kelas' => 'XI PM 3'],
            ['name' => 'Apri Yusup Firmansyah', 'kelas' => 'XI PM 3'],
            ['name' => 'Charmellyztha Sofia Hasna', 'kelas' => 'XI PM 3'],
            ['name' => 'Jingga Ayu Kendini', 'kelas' => 'XI PM 3'],
            ['name' => 'Khasifatun Nisa', 'kelas' => 'XI PM 3'],
            ['name' => 'May Va', 'kelas' => 'XI PM 3'],
            ['name' => 'Resty Aurellia Farhanna', 'kelas' => 'XI PM 3'],
            ['name' => 'Sungging Perdopo', 'kelas' => 'XI PM 3'],
            ['name' => 'Kalyca Ritza Sevtianti', 'kelas' => 'XI PPLG 1'],
            ['name' => 'Muhammad Ibnu Almaas', 'kelas' => 'XI PPLG 1'],
            ['name' => 'Lintang Ayu Prameswari', 'kelas' => 'XI PPLG 2'],
            ['name' => 'Nada Shofi Avrilia', 'kelas' => 'XI PPLG 2'],
            ['name' => 'Safa Adian Raphaela', 'kelas' => 'XI PPLG 2'],
            ['name' => 'Safa Zahira', 'kelas' => 'XI PPLG 2'],
            ['name' => 'Shandy Aulia', 'kelas' => 'XI PPLG 2'],
            ['name' => 'Thalita Amelia Barokah', 'kelas' => 'XI PPLG 2'],
            ['name' => 'Kalya Corelita Winata', 'kelas' => 'XI PPLG 3'],
            ['name' => 'Karina Salsabila Afandi', 'kelas' => 'XI PPLG 3'],
            ['name' => 'Linggan Puja Kusuma', 'kelas' => 'XI PPLG 3'],
            ['name' => 'Muhammad Bimasakti K. R.', 'kelas' => 'XI PPLG 3'],
            ['name' => 'Nazwa Aulia Fanti', 'kelas' => 'XI PPLG 3'],
            ['name' => 'Zilfa Azizah Tuh Zahro', 'kelas' => 'XI PPLG 3'],
            ['name' => 'Eka Ayu Septiani', 'kelas' => 'XI TF 1'],
            ['name' => 'Hilda Sabrina', 'kelas' => 'XI TF 1'],
            ['name' => 'Jasifa Auliya Putri', 'kelas' => 'XI TF 1'],
            ['name' => 'Rifqah Ulfa Rianti', 'kelas' => 'XI TF 1'],
            ['name' => 'Salma Kholbi Zahirah', 'kelas' => 'XI TF 1'],
            ['name' => 'Alfianti Putri Lestari', 'kelas' => 'XI TF 2'],
            ['name' => 'Amiliya Abda Chanifa', 'kelas' => 'XI TF 2'],
            ['name' => 'Ananda Syafiqa Amalia', 'kelas' => 'XI TF 2'],
            ['name' => 'Indah Kirana', 'kelas' => 'XI TF 2'],
            ['name' => 'Intan Nur Assyfa Putri', 'kelas' => 'XI TF 2'],
            ['name' => 'Tsalsa Sabrina', 'kelas' => 'XI TF 2'],
            ['name' => 'Allika Abiela Ananta', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Alfa Mulyasari Fauzan', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Amanda Putri Zizahra', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Bahjatunnafisah', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Fairy Zheta Ryu', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Kenezta Aolin Terico', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Zamia Hasna Rachelia', 'kelas' => 'XI TJKT 1'],
            ['name' => 'Kayana Felicia Zuleika', 'kelas' => 'XII AKL 1'],
            ['name' => 'Livina', 'kelas' => 'XII AKL 1'],
            ['name' => 'Risma Amelia', 'kelas' => 'XII AKL 1'],
            ['name' => 'Zein Vies Airinn Nabila Y', 'kelas' => 'XII AKL 1'],
            ['name' => 'Anggun Syaikhan Zalfa N', 'kelas' => 'XII AKL 2'],
            ['name' => 'Dwiana Shanti', 'kelas' => 'XII AKL 2'],
            ['name' => 'Anndin Eno Dwi Yuliani', 'kelas' => 'XII AKL 3'],
            ['name' => 'Rizka Rahmadhani', 'kelas' => 'XII AKL 3'],
            ['name' => 'Syavira Dinda Aurel', 'kelas' => 'XII AKL 3'],
            ['name' => 'Aisyah Sholihatunnisa', 'kelas' => 'XII AKL 4'],
            ['name' => 'Desi Mulyani', 'kelas' => 'XII AKL 4'],
            ['name' => 'Fadila Atut Khosiah', 'kelas' => 'XII AKL 4'],
            ['name' => 'Salsa Friscila Putri R', 'kelas' => 'XII AKL 4'],
            ['name' => 'Salsabila Maharani A', 'kelas' => 'XII AKL 4'],
            ['name' => 'Aulia Hanun Salwa Auriye', 'kelas' => 'XII AKL 5'],
            ['name' => 'Melia Berlianti', 'kelas' => 'XII AKL 5'],
            ['name' => 'Hindun Merdekawati A', 'kelas' => 'XII AKL 5'],
            ['name' => 'Nadissa Syifa Ramadhani', 'kelas' => 'XII AKL 5'],
            ['name' => 'Tri Asih Widyawati', 'kelas' => 'XII AKL 5'],
            ['name' => 'Yolanda Marvelia Putri', 'kelas' => 'XII AKL 5'],
            ['name' => 'Zainatul Hamimah', 'kelas' => 'XII AKL 5'],
            ['name' => 'Afka Salsabila', 'kelas' => 'XII DKV 1'],
            ['name' => 'Haaniyah Dwi Rahmawati', 'kelas' => 'XII DKV 1'],
            ['name' => 'Viony Rahmadhani', 'kelas' => 'XII DKV 1'],
            ['name' => 'Hasna\' Tsaniatul Nabila', 'kelas' => 'XII DKV 1'],
            ['name' => 'Haura Salsabilla', 'kelas' => 'XII DKV 1'],
            ['name' => 'Indah Meilani Putri', 'kelas' => 'XII DKV 1'],
            ['name' => 'Farraza Nuradine F', 'kelas' => 'XII DKV 2'],
            ['name' => 'Fatimah Hidayanti', 'kelas' => 'XII DKV 2'],
            ['name' => 'Reizelda Keulamasari', 'kelas' => 'XII DKV 2'],
            ['name' => 'Hanumara Arya Pramadi', 'kelas' => 'XII DKV 2'],
            ['name' => 'Muhammad Paaza Abidin', 'kelas' => 'XII DKV 2'],
            ['name' => 'Riska Ramadhani Putri', 'kelas' => 'XII DKV 2'],
            ['name' => 'Shayla Divania Putri', 'kelas' => 'XII DKV 2'],
            ['name' => 'Yusuf Hengki Fernando I', 'kelas' => 'XII DKV 2'],
            ['name' => 'Ri Zahrani Adila Sanjaya', 'kelas' => 'XII MPLB 1'],
            ['name' => 'Faiza Hidayati', 'kelas' => 'XII MPLB 1'],
            ['name' => 'Nathania Elysia Rahma', 'kelas' => 'XII MPLB 2'],
            ['name' => 'Rifa Amaliana', 'kelas' => 'XII MPLB 2'],
            ['name' => 'Afaf Nur Faizah', 'kelas' => 'XII MPLB 3'],
            ['name' => 'Nazarli Nadiah Putri Y', 'kelas' => 'XII MPLB 3'],
            ['name' => 'Benaya Iskha Ghifari', 'kelas' => 'XI MPLB 4'], // Note: User typed XI MPLB 4, keeping as provided
            ['name' => 'Syarifah Tazqiah', 'kelas' => 'XI MPLB 4'],
            ['name' => 'Bana Arvacelena Benggu', 'kelas' => 'XII PM 2'],
            ['name' => 'Dini Amalia Ismareta', 'kelas' => 'XII PM 2'],
            ['name' => 'Endina Aurelia Elfrida P', 'kelas' => 'XII PM 2'],
            ['name' => 'Fatwa Oktafiyani Putri', 'kelas' => 'XII PM 2'],
            ['name' => 'Hilda Nur Asiah', 'kelas' => 'XII PM 2'],
            ['name' => 'Irma Riskawati', 'kelas' => 'XII PM 2'],
            ['name' => 'Nafiza Rimahayu', 'kelas' => 'XII PM 2'],
            ['name' => 'Ria Nur Syafa', 'kelas' => 'XII PM 2'],
            ['name' => 'Alifah Devina', 'kelas' => 'XII PM 3'],
            ['name' => 'Arghya Rachma Aqeela', 'kelas' => 'XII PM 3'],
            ['name' => 'Fadila Ristianingsih', 'kelas' => 'XII PM 3'],
            ['name' => 'Florisa Sabria Zahra', 'kelas' => 'XII PM 3'],
            ['name' => 'Luthfiana Hasna', 'kelas' => 'XII PM 3'],
            ['name' => 'Lutviana Nur Nafi\'ah', 'kelas' => 'XII PM 3'],
            ['name' => 'Shafa Nuril \'Azmi', 'kelas' => 'XII PM 3'],
            ['name' => 'Shamita Aisha Sabrina', 'kelas' => 'XII PM 3'],
            ['name' => 'Rofiif Agustina', 'kelas' => 'XII PPLG 1'],
            ['name' => 'Safa Staqib Adzkiya', 'kelas' => 'XII PPLG 1'],
            ['name' => 'Zalfa Zahirah', 'kelas' => 'XII PPLG 1'],
            ['name' => 'Afriza Nur \'Aini', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Reva Nagali Aningtyas', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Nasywa Tsaqib Oxyana H.', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Sekarwangi Putri', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Adelia Nur Ramadhan', 'kelas' => 'XII TF 1'],
            ['name' => 'Caca Cahyati Rahmadhani', 'kelas' => 'XII TF 1'],
            ['name' => 'Naydhira Zafkha Juanesya', 'kelas' => 'XII TF 1'],
            ['name' => 'Rovikoh', 'kelas' => 'XII TF 1'],
            ['name' => 'Alyaa Hanifah Putri', 'kelas' => 'XII TF 2'],
            ['name' => 'Queensha Vivin Arshela', 'kelas' => 'XII TF 2'],
            ['name' => 'Umnia Sari', 'kelas' => 'XII TF 2'],
            ['name' => 'Aqiilah Mufiida Zahraa', 'kelas' => 'XII TJKT 1'],
            ['name' => 'Amira Syifa Rizki V', 'kelas' => 'XII TJKT 1'],
            ['name' => 'Zazqia Aura Ramadhani', 'kelas' => 'XII TJKT 1'],
        ];

        $existingUsers = User::all();
        $userByName = [];
        foreach ($existingUsers as $u) {
            $normalized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $u->name));
            $userByName[$normalized] = $u;
        }

        $existingEmails = User::pluck('email')->toArray();
        $emailMap = array_fill_keys(array_map('strtolower', $existingEmails), true);

        foreach ($members as $m) {
            $rawName = $m['name'];
            $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $rawName);
            $normalizedName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $rawName));
            $parts = explode(' ', trim($cleanName));
            $firstName = strtolower($parts[0] ?? 'anggota');

            // 1. Check if user already exists
            $user = $userByName[$normalizedName] ?? null;

            // Also check partial if pengurus name was missing last letter/initial
            if (! $user) {
                foreach ($userByName as $normPName => $pUser) {
                    if (str_starts_with($normalizedName, $normPName) || str_starts_with($normPName, $normalizedName)) {
                        // Ensure length is close (to avoid matching just first name)
                        if (abs(strlen($normalizedName) - strlen($normPName)) <= 3) {
                            $user = $pUser;
                            break;
                        }
                    }
                }
            }

            // 2. If not found, create as regular member (<nama_depan>_anggota@gmail.com)
            if (! $user) {
                $baseEmail = "{$firstName}_anggota@gmail.com";
                $email = $baseEmail;
                $counter = 2;
                while (isset($emailMap[$email])) {
                    $email = "{$firstName}{$counter}_anggota@gmail.com";
                    $counter++;
                }
                $emailMap[$email] = true;

                $user = User::create([
                    'name' => $rawName,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'USER',
                    'kelas' => $m['kelas'],
                    'status_verifikasi' => 'APPROVED',
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('USER');
                $userByName[$normalizedName] = $user;
            }

            // 3. Link or create member
            Member::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'organization_period_id' => $period->id,
                    'name' => $rawName,
                    'kelas' => $m['kelas'],
                    'status' => 'AKTIF',
                ]
            );
        }
    }
}
