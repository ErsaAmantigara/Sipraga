<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\ProfilePelanggan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $ulu    = Cabang::where('nama_cabang', 'Ulu')->first()?->cabang_id;
        $ilir   = Cabang::where('nama_cabang', 'Ilir')->first()?->cabang_id;
        $demang = Cabang::where('nama_cabang', 'Demang')->first()?->cabang_id;

        $this->seedRoleUser('Super Admin', '089527191000', null, 'super-admin');

        $this->seedRoleUser('Cs Ulu', '089527191011', $ulu, 'customer_service');
        $this->seedRoleUser('Cs Ilir', '089527191012', $ilir, 'customer_service');
        $this->seedRoleUser('Cs Demang', '089527191013', $demang, 'customer_service');

        $this->seedRoleUser('Koordinator Ulu', '089527191014', $ulu, 'koordinator_teknisi');
        $this->seedRoleUser('Koordinator Ilir', '089527191015', $ilir, 'koordinator_teknisi');
        $this->seedRoleUser('Koordinator Demang', '089527191016', $demang, 'koordinator_teknisi');

        $this->seedRoleUser('Teknisi Ulu', '089527191017', $ulu, 'teknisi');
        $this->seedRoleUser('Teknisi Ilir', '089527191018', $ilir, 'teknisi');
        $this->seedRoleUser('Teknisi Demang', '089527191019', $demang, 'teknisi');

        $this->seedRoleUser('Asmen Ulu', '089527191020', $ulu, 'asisten_manager');
        $this->seedRoleUser('Asmen Ilir', '089527191021', $ilir, 'asisten_manager');
        $this->seedRoleUser('Asmen Demang', '089527191022', $demang, 'asisten_manager');

        $this->seedRoleUser('Manager', '089527191023', null, 'manager');

        $this->seedPelangganUser('Pelanggan Ulu', '089527191024', $ulu, 'LP-3-0001363', 'Jl. Ulu Raya No. 1', -3.00250000, 104.76120000);
        $this->seedPelangganUser('Pelanggan Ilir', '089527191025', $ilir, 'LP-2-0001362', 'Jl. Ilir Permai No. 2', -2.94320000, 104.77180000);
        $this->seedPelangganUser('Pelanggan Demang', '089527191026', $demang, 'LP-1-0001361', 'Jl. Demang Baru No. 3', -2.97280000, 104.73450000);
    }

    private function seedRoleUser(string $name, string $phone, ?int $cabangId, string $role): void
    {
        $user = User::updateOrCreate(
            ['no_hp' => $phone],
            [
                'name' => $name,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'cabang_id' => $cabangId,
            ]
        );

        $user->syncRoles([$role]);
    }

    private function seedPelangganUser(
        string $name,
        string $phone,
        int $cabangId,
        string $noIdPelanggan,
        string $alamat,
        float $latitude,
        float $longitude
    ): void {
        $user = User::updateOrCreate(
            ['no_hp' => $phone],
            [
                'name' => $name,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'cabang_id' => $cabangId,
            ]
        );

        $user->syncRoles(['pelanggan']);

        ProfilePelanggan::updateOrCreate(
            ['user_id' => $user->user_id],
            [
                'no_id_pelanggan' => $noIdPelanggan,
                'jenis_pelanggan' => 'R1',
                'alamat' => $alamat,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]
        );
    }
}
