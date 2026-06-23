<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Pengerjaan;
use App\Models\ProfilePelanggan;
use App\Models\Pengaduan;
use App\Models\User;
use App\Http\Controllers\PenilaianSawController;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengaduanSelesaiSeeder extends Seeder
{
    public function run(): void
    {
        $cabangDemang = Cabang::where('nama_cabang', 'Demang')->first();
        $cabangUlu    = Cabang::where('nama_cabang', 'Ulu')->first();
        $cabangIlir   = Cabang::where('nama_cabang', 'Ilir')->first();

        if ($cabangDemang) $this->seedBranch($cabangDemang, 'DEMANG', 1);
        if ($cabangUlu)    $this->seedBranch($cabangUlu, 'ULU', 1);
        if ($cabangIlir)   $this->seedBranch($cabangIlir, 'ILIR', 1);

        $this->recalculateSawRankings();
    }

    private function seedBranch(Cabang $cabang, string $code, int $start): void
    {
        $now = Carbon::now();

        $alternatives = match ($code) {
            'DEMANG' => [
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start),
                    'tanggal' => $now->copy()->subDays(7),
                    'jenis' => 'Pipa Bocor',
                    'deskripsi' => 'Pipa gas bocor di bagian sambungan dapur, tercium bau gas menyengat.',
                    'stand_meter' => 1450,
                    'pelanggan' => [
                        'name' => 'Ahmad Fauzi Demang',
                        'no_hp' => '089527191201',
                        'no_id' => 'LP-9-0001364',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Demang Lebar Daun No. 15, Demang',
                        'latitude' => -2.96700000,
                        'longitude' => 104.71200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 1),
                    'tanggal' => $now->copy()->subDays(6)->subHours(4),
                    'jenis' => 'Pipa Service',
                    'deskripsi' => 'Permintaan perawatan pipa gas secara berkala.',
                    'stand_meter' => 1520,
                    'pelanggan' => [
                        'name' => 'Budi Santoso Demang',
                        'no_hp' => '089527191202',
                        'no_id' => 'SA-9-0001365',
                        'jenis' => 'PK1',
                        'alamat' => 'Jl. Gubernur H. A. Bastari No. 8, Demang',
                        'latitude' => -2.97200000,
                        'longitude' => 104.71800000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 2),
                    'tanggal' => $now->copy()->subDays(6),
                    'jenis' => 'Rekening Tagihan',
                    'deskripsi' => 'Tagihan gas bulan ini tidak sesuai dengan pemakaian.',
                    'stand_meter' => 1180,
                    'pelanggan' => [
                        'name' => 'Citra Dewi Demang',
                        'no_hp' => '089527191203',
                        'no_id' => 'DL-9-0001366',
                        'jenis' => 'R2',
                        'alamat' => 'Jl. Residen Abdul Rozak No. 22, Demang',
                        'latitude' => -2.97600000,
                        'longitude' => 104.72500000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 3),
                    'tanggal' => $now->copy()->subDays(5)->subHours(2),
                    'jenis' => 'Meter Gas Rusak',
                    'deskripsi' => 'Meter gas tidak berputar meskipun kompor menyala.',
                    'stand_meter' => 1210,
                    'pelanggan' => [
                        'name' => 'Deni Saputra Demang',
                        'no_hp' => '089527191204',
                        'no_id' => 'LP-9-0001367',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Demang Lebar Daun No. 30, Demang',
                        'latitude' => -2.98000000,
                        'longitude' => 104.73000000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 4),
                    'tanggal' => $now->copy()->subDays(5),
                    'jenis' => 'Kompor Tidak Hidup',
                    'deskripsi' => 'Kompor gas tidak mau menyala meskipun gas tersedia.',
                    'stand_meter' => 1340,
                    'pelanggan' => [
                        'name' => 'Eka Pratiwi Demang',
                        'no_hp' => '089527191205',
                        'no_id' => 'SA-9-0001368',
                        'jenis' => 'PK2',
                        'alamat' => 'Jl. Gubernur H. A. Bastari No. 12, Demang',
                        'latitude' => -2.98400000,
                        'longitude' => 104.71500000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 5),
                    'tanggal' => $now->copy()->subDays(4)->subHours(6),
                    'jenis' => 'Api Kompor Kecil',
                    'deskripsi' => 'Api pada kompor gas sangat kecil meskipun regulator sudah penuh.',
                    'stand_meter' => 1280,
                    'pelanggan' => [
                        'name' => 'Fajar Hidayat Demang',
                        'no_hp' => '089527191206',
                        'no_id' => 'DL-9-0001369',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Residen Abdul Rozak No. 5, Demang',
                        'latitude' => -2.98800000,
                        'longitude' => 104.72200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 6),
                    'tanggal' => $now->copy()->subDays(4),
                    'jenis' => 'Perubahan Instalasi Pipa',
                    'deskripsi' => 'Permintaan perubahan jalur instalasi pipa karena renovasi rumah.',
                    'stand_meter' => 1090,
                    'pelanggan' => [
                        'name' => 'Gita Permata Demang',
                        'no_hp' => '089527191207',
                        'no_id' => 'LP-9-0001370',
                        'jenis' => 'PK1',
                        'alamat' => 'Jl. Demang Lebar Daun No. 45, Demang',
                        'latitude' => -2.97500000,
                        'longitude' => 104.73200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 7),
                    'tanggal' => $now->copy()->subDays(3)->subHours(3),
                    'jenis' => 'Perubahan Instalasi Meteran',
                    'deskripsi' => 'Permintaan pemindahan meteran gas ke lokasi yang lebih mudah diakses.',
                    'stand_meter' => 1410,
                    'pelanggan' => [
                        'name' => 'Hendra Gunawan Demang',
                        'no_hp' => '089527191208',
                        'no_id' => 'SA-9-0001371',
                        'jenis' => 'R2',
                        'alamat' => 'Jl. Gubernur H. A. Bastari No. 20, Demang',
                        'latitude' => -2.96900000,
                        'longitude' => 104.72700000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 8),
                    'tanggal' => $now->copy()->subDays(2),
                    'jenis' => 'Penambahan Titik Api',
                    'deskripsi' => 'Permintaan penambahan titik api untuk kompor baru di dapur.',
                    'stand_meter' => 1365,
                    'pelanggan' => [
                        'name' => 'Indah Lestari Demang',
                        'no_hp' => '089527191209',
                        'no_id' => 'DL-9-0001372',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Residen Abdul Rozak No. 18, Demang',
                        'latitude' => -2.98200000,
                        'longitude' => 104.72000000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-DEMANG-%03d', $start + 9),
                    'tanggal' => $now->copy()->subDay()->subHours(5),
                    'jenis' => 'Instalasi Kompor Baru',
                    'deskripsi' => 'Pemasangan kompor gas baru untuk pelanggan baru.',
                    'stand_meter' => 1250,
                    'pelanggan' => [
                        'name' => 'Joko Widodo Demang',
                        'no_hp' => '089527191210',
                        'no_id' => 'LP-9-0001373',
                        'jenis' => 'PK2',
                        'alamat' => 'Jl. Demang Lebar Daun No. 50, Demang',
                        'latitude' => -2.99000000,
                        'longitude' => 104.73300000,
                    ],
                ],
            ],
            'ULU' => [
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start),
                    'tanggal' => $now->copy()->subDays(7),
                    'jenis' => 'Pipa Bocor',
                    'deskripsi' => 'Kebocoran pipa gas di halaman belakang rumah.',
                    'stand_meter' => 1620,
                    'pelanggan' => [
                        'name' => 'Agus Wijaya Ulu',
                        'no_hp' => '089527191211',
                        'no_id' => 'LP-9-0001374',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Jakabaring No. 10, Ulu',
                        'latitude' => -3.00500000,
                        'longitude' => 104.76200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 1),
                    'tanggal' => $now->copy()->subDays(6)->subHours(5),
                    'jenis' => 'Pipa Service',
                    'deskripsi' => 'Permintaan perawatan pipa gas rutin untuk keamanan.',
                    'stand_meter' => 1710,
                    'pelanggan' => [
                        'name' => 'Bambang Supriadi Ulu',
                        'no_hp' => '089527191212',
                        'no_id' => 'SA-9-0001375',
                        'jenis' => 'PK1',
                        'alamat' => 'Jl. K.H. Wahid Hasyim No. 25, Ulu',
                        'latitude' => -3.01000000,
                        'longitude' => 104.76800000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 2),
                    'tanggal' => $now->copy()->subDays(6),
                    'jenis' => 'Rekening Tagihan',
                    'deskripsi' => 'Tagihan melonjak drastis dibanding bulan sebelumnya.',
                    'stand_meter' => 1530,
                    'pelanggan' => [
                        'name' => 'Cahya Ningsih Ulu',
                        'no_hp' => '089527191213',
                        'no_id' => 'DL-9-0001376',
                        'jenis' => 'R2',
                        'alamat' => 'Jl. Mayor Salim Batubara No. 7, Ulu',
                        'latitude' => -3.01500000,
                        'longitude' => 104.77500000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 3),
                    'tanggal' => $now->copy()->subDays(5)->subHours(3),
                    'jenis' => 'Meter Gas Rusak',
                    'deskripsi' => 'Meter gas berputar terus meskipun semua kompor mati.',
                    'stand_meter' => 1480,
                    'pelanggan' => [
                        'name' => 'Dian Puspita Ulu',
                        'no_hp' => '089527191214',
                        'no_id' => 'LP-9-0001377',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Jakabaring No. 15, Ulu',
                        'latitude' => -3.02000000,
                        'longitude' => 104.78000000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 4),
                    'tanggal' => $now->copy()->subDays(5),
                    'jenis' => 'Kompor Tidak Hidup',
                    'deskripsi' => 'Kompor gas tidak bisa dinyalakan sama sekali.',
                    'stand_meter' => 1350,
                    'pelanggan' => [
                        'name' => 'Eko Prasetyo Ulu',
                        'no_hp' => '089527191215',
                        'no_id' => 'SA-9-0001378',
                        'jenis' => 'PK2',
                        'alamat' => 'Jl. K.H. Wahid Hasyim No. 30, Ulu',
                        'latitude' => -3.02500000,
                        'longitude' => 104.78500000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 5),
                    'tanggal' => $now->copy()->subDays(4)->subHours(7),
                    'jenis' => 'Api Kompor Kecil',
                    'deskripsi' => 'Api kompor sangat kecil dan berwarna oranye, tidak biru.',
                    'stand_meter' => 1290,
                    'pelanggan' => [
                        'name' => 'Fitriani Ulu',
                        'no_hp' => '089527191216',
                        'no_id' => 'DL-9-0001379',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Mayor Salim Batubara No. 12, Ulu',
                        'latitude' => -3.03000000,
                        'longitude' => 104.79000000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 6),
                    'tanggal' => $now->copy()->subDays(4),
                    'jenis' => 'Perubahan Instalasi Pipa',
                    'deskripsi' => 'Permintaan relokasi pipa gas karena pembangunan kamar baru.',
                    'stand_meter' => 1400,
                    'pelanggan' => [
                        'name' => 'Gilang Ramadhan Ulu',
                        'no_hp' => '089527191217',
                        'no_id' => 'LP-9-0001380',
                        'jenis' => 'PK1',
                        'alamat' => 'Jl. Jakabaring No. 20, Ulu',
                        'latitude' => -3.00800000,
                        'longitude' => 104.76500000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 7),
                    'tanggal' => $now->copy()->subDays(3)->subHours(4),
                    'jenis' => 'Perubahan Instalasi Meteran',
                    'deskripsi' => 'Permintaan pemindahan meteran ke luar rumah.',
                    'stand_meter' => 1550,
                    'pelanggan' => [
                        'name' => 'Hesti Purwanti Ulu',
                        'no_hp' => '089527191218',
                        'no_id' => 'SA-9-0001381',
                        'jenis' => 'R2',
                        'alamat' => 'Jl. K.H. Wahid Hasyim No. 35, Ulu',
                        'latitude' => -3.01200000,
                        'longitude' => 104.77200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 8),
                    'tanggal' => $now->copy()->subDays(2),
                    'jenis' => 'Penambahan Titik Api',
                    'deskripsi' => 'Permintaan tambahan titik api untuk usaha katering.',
                    'stand_meter' => 1630,
                    'pelanggan' => [
                        'name' => 'Irfan Hakim Ulu',
                        'no_hp' => '089527191219',
                        'no_id' => 'DL-9-0001382',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Mayor Salim Batubara No. 18, Ulu',
                        'latitude' => -3.01800000,
                        'longitude' => 104.77800000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ULU-%03d', $start + 9),
                    'tanggal' => $now->copy()->subDay()->subHours(6),
                    'jenis' => 'Cabut Meteran (Stop Berlangganan)',
                    'deskripsi' => 'Permintaan penghentian langganan karena pindah rumah.',
                    'stand_meter' => 1750,
                    'pelanggan' => [
                        'name' => 'Juli Andriani Ulu',
                        'no_hp' => '089527191220',
                        'no_id' => 'LP-9-0001383',
                        'jenis' => 'PK2',
                        'alamat' => 'Jl. Jakabaring No. 25, Ulu',
                        'latitude' => -3.02200000,
                        'longitude' => 104.78800000,
                    ],
                ],
            ],
            'ILIR' => [
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start),
                    'tanggal' => $now->copy()->subDays(7),
                    'jenis' => 'Pipa Bocor',
                    'deskripsi' => 'Tercium bau gas dari dalam tanah di dekat meteran.',
                    'stand_meter' => 1130,
                    'pelanggan' => [
                        'name' => 'Adi Nugroho Ilir',
                        'no_hp' => '089527191221',
                        'no_id' => 'LP-9-0001384',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Sapta Marga No. 12, Ilir',
                        'latitude' => -2.93200000,
                        'longitude' => 104.77200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 1),
                    'tanggal' => $now->copy()->subDays(6)->subHours(6),
                    'jenis' => 'Pipa Service',
                    'deskripsi' => 'Permintaan pemeriksaan dan servis pipa gas tahunan.',
                    'stand_meter' => 1210,
                    'pelanggan' => [
                        'name' => 'Bayu Aji Ilir',
                        'no_hp' => '089527191222',
                        'no_id' => 'SA-9-0001385',
                        'jenis' => 'PK1',
                        'alamat' => 'Jl. Soekarno Hatta No. 8, Ilir',
                        'latitude' => -2.93600000,
                        'longitude' => 104.77800000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 2),
                    'tanggal' => $now->copy()->subDays(6),
                    'jenis' => 'Rekening Tagihan',
                    'deskripsi' => 'Tagihan tidak sesuai dengan rata-rata pemakaian bulanan.',
                    'stand_meter' => 1060,
                    'pelanggan' => [
                        'name' => 'Christina Wulandari Ilir',
                        'no_hp' => '089527191223',
                        'no_id' => 'DL-9-0001386',
                        'jenis' => 'R2',
                        'alamat' => 'Jl. Jend. Sudirman No. 15, Ilir',
                        'latitude' => -2.94000000,
                        'longitude' => 104.78400000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 3),
                    'tanggal' => $now->copy()->subDays(5)->subHours(4),
                    'jenis' => 'Meter Gas Rusak',
                    'deskripsi' => 'Meter gas macet dan tidak mencatat pemakaian.',
                    'stand_meter' => 1180,
                    'pelanggan' => [
                        'name' => 'Dedi Kurniawan Ilir',
                        'no_hp' => '089527191224',
                        'no_id' => 'LP-9-0001387',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Sapta Marga No. 20, Ilir',
                        'latitude' => -2.94400000,
                        'longitude' => 104.79000000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 4),
                    'tanggal' => $now->copy()->subDays(5),
                    'jenis' => 'Kompor Tidak Hidup',
                    'deskripsi' => 'Kompor gas tidak menyala setelah tabung diganti.',
                    'stand_meter' => 1005,
                    'pelanggan' => [
                        'name' => 'Elsa Safitri Ilir',
                        'no_hp' => '089527191225',
                        'no_id' => 'SA-9-0001388',
                        'jenis' => 'PK2',
                        'alamat' => 'Jl. Soekarno Hatta No. 25, Ilir',
                        'latitude' => -2.94800000,
                        'longitude' => 104.79400000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 5),
                    'tanggal' => $now->copy()->subDays(4)->subHours(8),
                    'jenis' => 'Api Kompor Kecil',
                    'deskripsi' => 'Api pada kompor sangat kecil, memasak jadi lama.',
                    'stand_meter' => 1150,
                    'pelanggan' => [
                        'name' => 'Farhan Maulana Ilir',
                        'no_hp' => '089527191226',
                        'no_id' => 'DL-9-0001389',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Jend. Sudirman No. 22, Ilir',
                        'latitude' => -2.95200000,
                        'longitude' => 104.77600000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 6),
                    'tanggal' => $now->copy()->subDays(4),
                    'jenis' => 'Perubahan Instalasi Pipa',
                    'deskripsi' => 'Permintaan penambahan jalur pipa ke area belakang rumah.',
                    'stand_meter' => 1090,
                    'pelanggan' => [
                        'name' => 'Galuh Ayu Ilir',
                        'no_hp' => '089527191227',
                        'no_id' => 'LP-9-0001390',
                        'jenis' => 'PK1',
                        'alamat' => 'Jl. Sapta Marga No. 30, Ilir',
                        'latitude' => -2.93800000,
                        'longitude' => 104.78200000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 7),
                    'tanggal' => $now->copy()->subDays(3)->subHours(5),
                    'jenis' => 'Perubahan Instalasi Meteran',
                    'deskripsi' => 'Permintaan pemindahan meteran gas ke dinding depan.',
                    'stand_meter' => 1240,
                    'pelanggan' => [
                        'name' => 'Hari Setiawan Ilir',
                        'no_hp' => '089527191228',
                        'no_id' => 'SA-9-0001391',
                        'jenis' => 'R2',
                        'alamat' => 'Jl. Soekarno Hatta No. 35, Ilir',
                        'latitude' => -2.94200000,
                        'longitude' => 104.78800000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 8),
                    'tanggal' => $now->copy()->subDays(2),
                    'jenis' => 'Penambahan Titik Api',
                    'deskripsi' => 'Permintaan tambahan titik api untuk usaha rumah makan.',
                    'stand_meter' => 1310,
                    'pelanggan' => [
                        'name' => 'Intan Permata Sari Ilir',
                        'no_hp' => '089527191229',
                        'no_id' => 'DL-9-0001392',
                        'jenis' => 'R1',
                        'alamat' => 'Jl. Jend. Sudirman No. 28, Ilir',
                        'latitude' => -2.94600000,
                        'longitude' => 104.77400000,
                    ],
                ],
                [
                    'nomor' => sprintf('PGD-ILIR-%03d', $start + 9),
                    'tanggal' => $now->copy()->subDay()->subHours(7),
                    'jenis' => 'Instalasi Kompor Baru',
                    'deskripsi' => 'Pemasangan kompor gas untuk dapur usaha baru.',
                    'stand_meter' => 1370,
                    'pelanggan' => [
                        'name' => 'Jaka Susila Ilir',
                        'no_hp' => '089527191230',
                        'no_id' => 'LP-9-0001393',
                        'jenis' => 'PK2',
                        'alamat' => 'Jl. Sapta Marga No. 40, Ilir',
                        'latitude' => -2.95400000,
                        'longitude' => 104.79200000,
                    ],
                ],
            ],
        };

        $teknisi = User::whereHas('roles', fn($q) => $q->where('name', 'teknisi'))
            ->where('cabang_id', $cabang->cabang_id)
            ->first();

        foreach ($alternatives as $item) {
            $pelanggan = $this->resolvePelanggan($item['pelanggan'], $cabang);

            $tanggalSelesai = $item['tanggal']->copy()->addHours(rand(1, 3));

            $pengaduan = Pengaduan::updateOrCreate(
                ['nomor_pengaduan' => $item['nomor']],
                [
                    'user_id' => $pelanggan->user_id,
                    'tanggal_pengaduan' => $item['tanggal'],
                    'tanggal_selesai' => $tanggalSelesai,
                    'jenis_keluhan' => $item['jenis'],
                    'deskripsi_keluhan' => $item['deskripsi'],
                    'stand_meter_terakhir' => $item['stand_meter'],
                    'foto_keluhan' => 'demo-keluhan.svg',
                    'status_pengaduan' => 'selesai',
                ]
            );

            if ($teknisi) {
                Pengerjaan::updateOrCreate(
                    ['pengaduan_id' => $pengaduan->pengaduan_id],
                    [
                        'user_id' => $teknisi->user_id,
                        'tanggal_mulai' => $item['tanggal'],
                        'tanggal_selesai' => $tanggalSelesai,
                        'status_pengerjaan' => 'selesai',
                        'foto_sebelum' => 'demo-foto-sebelum.svg',
                        'foto_proses' => 'demo-foto-proses.svg',
                        'foto_sesudah' => 'demo-foto-sesudah.svg',
                        'keterangan_teknisi' => 'Pengerjaan selesai sesuai prosedur.',
                        'material' => 'pipa, coupling, sealant',
                        'created_at' => $item['tanggal'],
                        'updated_at' => $tanggalSelesai,
                    ]
                );
            }
        }
    }

    private function resolvePelanggan(array $data, Cabang $cabang): User
    {
        $user = User::updateOrCreate(
            ['no_hp' => $data['no_hp']],
            [
                'name' => $data['name'],
                'password' => Hash::make('password123'),
                'is_active' => true,
                'cabang_id' => $cabang->cabang_id,
            ]
        );

        $user->syncRoles(['pelanggan']);

        ProfilePelanggan::updateOrCreate(
            ['user_id' => $user->user_id],
            [
                'no_id_pelanggan' => $data['no_id'],
                'jenis_pelanggan' => $data['jenis'],
                'alamat' => $data['alamat'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]
        );

        return $user;
    }

    private function recalculateSawRankings(): void
    {
        app(PenilaianSawController::class)->processAll();
    }
}
