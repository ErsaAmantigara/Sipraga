<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\ProfilePelanggan;
use App\Models\Pengaduan;
use App\Models\User;
use App\Http\Controllers\PenilaianSawController;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoFlowSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = Cabang::where('nama_cabang', 'Demang')->first();

        if (! $cabang) {
            return;
        }

        $this->seedBranchFlow($cabang);
    }

    private function seedBranchFlow(Cabang $cabang): void
    {
        $keluhanService = 'Pipa Service';
        $keluhanBocor = 'Pipa Bocor';
        $keluhanMeter = 'Meter Gas Rusak';
        $keluhanTitik = 'Penambahan Titik Api';

        $alternatives = [
            [
                'nomor' => 'PGD-DEMANG-011',
                'tanggal' => Carbon::now()->subHours(10),
                'jenis' => $keluhanService,
                'deskripsi' => 'Permasalahan pada pipa gas di cabang Demang.',
                'stand_meter' => 1210,
                'pelanggan' => [
                    'name' => 'Ersa Amantigara',
                    'no_hp' => '089527191101',
                    'no_id' => 'LP-9-0001359',
                    'jenis' => 'PK1',
                    'alamat' => 'Jl. Merdeka No. 1, Demang',
                    'latitude' => -3.01500000,
                    'longitude' => 104.75000000,
                ],
            ],
            [
                'nomor' => 'PGD-DEMANG-012',
                'tanggal' => Carbon::now()->subHours(7),
                'jenis' => $keluhanBocor,
                'deskripsi' => 'Kebocoran pipa gas di area dapur pelanggan cabang Demang.',
                'stand_meter' => 1225,
                'pelanggan' => [
                    'name' => 'Pelanggan A2 Demang',
                    'no_hp' => '089527191102',
                    'no_id' => 'SA-9-0001360',
                    'jenis' => 'PK2',
                    'alamat' => 'Jl. Sudirman No. 10, Demang',
                    'latitude' => -2.98000000,
                    'longitude' => 104.72400000,
                ],
            ],
            [
                'nomor' => 'PGD-DEMANG-013',
                'tanggal' => Carbon::now()->subHours(7),
                'jenis' => $keluhanMeter,
                'deskripsi' => 'Meter gas tidak berfungsi di cabang Demang.',
                'stand_meter' => 1280,
                'pelanggan' => [
                    'name' => 'Pelanggan A3 Demang',
                    'no_hp' => '089527191103',
                    'no_id' => 'DL-9-0001361',
                    'jenis' => 'R2',
                    'alamat' => 'Jl. Ahmad Yani No. 5, Demang',
                    'latitude' => -2.95500000,
                    'longitude' => 104.74000000,
                ],
            ],
            [
                'nomor' => 'PGD-DEMANG-014',
                'tanggal' => Carbon::now()->subHours(5),
                'jenis' => $keluhanTitik,
                'deskripsi' => 'Permintaan penambahan titik api di cabang Demang.',
                'stand_meter' => 1315,
                'pelanggan' => [
                    'name' => 'Pelanggan A4 Demang',
                    'no_hp' => '089527191104',
                    'no_id' => 'SA-9-0001362',
                    'jenis' => 'R1',
                    'alamat' => 'Jl. Diponegoro No. 8, Demang',
                    'latitude' => -2.92000000,
                    'longitude' => 104.69000000,
                ],
            ],
            [
                'nomor' => 'PGD-DEMANG-015',
                'tanggal' => Carbon::now()->subHours(1),
                'jenis' => $keluhanMeter,
                'deskripsi' => 'Meter gas tidak bergerak meskipun ada pemakaian di cabang Demang.',
                'stand_meter' => 1338,
                'pelanggan' => [
                    'name' => 'Pelanggan A5 Demang',
                    'no_hp' => '089527191105',
                    'no_id' => 'LP-9-0001363',
                    'jenis' => 'R1',
                    'alamat' => 'Jl. Pahlawan No. 3, Demang',
                    'latitude' => -2.97800000,
                    'longitude' => 104.72300000,
                ],
            ],
        ];

        foreach ($alternatives as $item) {
            $pelanggan = $this->resolvePelanggan($item['pelanggan'], $cabang);

            $pengaduan = Pengaduan::updateOrCreate(
                ['nomor_pengaduan' => $item['nomor']],
                [
                    'user_id' => $pelanggan->user_id,
                    'tanggal_pengaduan' => $item['tanggal'],
                    'tanggal_selesai' => null,
                    'jenis_keluhan' => $item['jenis'],
                    'deskripsi_keluhan' => $item['deskripsi'],
                    'stand_meter_terakhir' => $item['stand_meter'],
                    'foto_keluhan' => 'demo-keluhan.svg',
                    'status_pengaduan' => 'valid',
                    'keterangan_cs' => null,
                ]
            );

        }

        $this->recalculateSawRankings();

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
