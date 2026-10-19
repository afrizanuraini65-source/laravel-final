<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterMember;

class MasterMemberSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'nisn' => '0081234561',
                'name' => 'Muhammad Rizky Pratama',
                'kelas' => 'X PPLG 1',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2008-04-12',
                'no_hp' => '081234567801',
                'catatan' => 'Calon anggota PIK-R angkatan 2026/2027',
            ],
            [
                'nisn' => '0081234562',
                'name' => 'Siti Nur Aisyah',
                'kelas' => 'X AKL 2',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2008-07-25',
                'no_hp' => '081234567802',
                'catatan' => 'Calon anggota PIK-R angkatan 2026/2027',
            ],
            [
                'nisn' => '0081234563',
                'name' => 'Dimas Arya Saputra',
                'kelas' => 'X DKV 1',
                'tempat_lahir' => 'Tegal',
                'tanggal_lahir' => '2008-01-15',
                'no_hp' => '081234567803',
                'catatan' => 'Calon anggota PIK-R angkatan 2026/2027',
            ],
            [
                'nisn' => '0081234564',
                'name' => 'Nabila Putri Ramadhani',
                'kelas' => 'X MPLB 2',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2008-09-30',
                'no_hp' => '081234567804',
                'catatan' => 'Calon anggota PIK-R angkatan 2026/2027',
            ],
            [
                'nisn' => '0081234565',
                'name' => 'Bagas Fajar Nugroho',
                'kelas' => 'X PM 1',
                'tempat_lahir' => 'Cirebon',
                'tanggal_lahir' => '2008-03-05',
                'no_hp' => '081234567805',
                'catatan' => 'Calon anggota PIK-R angkatan 2026/2027',
            ],
            [
                'nisn' => '0071234566',
                'name' => 'Kayla Amanda Azzahra',
                'kelas' => 'XI PPLG 2',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2007-11-18',
                'no_hp' => '081234567806',
                'catatan' => 'Calon anggota PIK-R kelas XI',
            ],
            [
                'nisn' => '0071234567',
                'name' => 'Fathir Al Ghifari',
                'kelas' => 'XI AKL 4',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2007-06-22',
                'no_hp' => '081234567807',
                'catatan' => 'Calon anggota PIK-R kelas XI',
            ],
            [
                'nisn' => '0071234568',
                'name' => 'Nayla Syakira Humaira',
                'kelas' => 'XI DKV 2',
                'tempat_lahir' => 'Tegal',
                'tanggal_lahir' => '2007-08-14',
                'no_hp' => '081234567808',
                'catatan' => 'Calon anggota PIK-R kelas XI',
            ],
            [
                'nisn' => '0071234569',
                'name' => 'Raditya Dwi Permana',
                'kelas' => 'XI MPLB 1',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2007-12-01',
                'no_hp' => '081234567809',
                'catatan' => 'Calon anggota PIK-R kelas XI',
            ],
            [
                'nisn' => '0071234570',
                'name' => 'Zahra Aurelia Putri',
                'kelas' => 'XI PM 2',
                'tempat_lahir' => 'Brebes',
                'tanggal_lahir' => '2007-05-19',
                'no_hp' => '081234567810',
                'catatan' => 'Calon anggota PIK-R kelas XI',
            ],
        ];

        foreach ($students as $student) {
            MasterMember::updateOrCreate(
                ['nisn' => $student['nisn']],
                $student
            );
        }
    }
}
