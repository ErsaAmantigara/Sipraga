<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'pengaduan.view',
            'pengaduan.create',
            'pengaduan.edit',
            'pengaduan.delete',
            'pengaduan.validate',
            'pengaduan.assign-teknisi',
            'pengerjaan.view',
            'pengerjaan.edit',
            'pengerjaan.rating',
            'laporan.view',
            'laporan-pengaduan.view',
            'laporan-pengerjaan.view',
            'cabang.view',
            'cabang.create',
            'cabang.edit',
            'cabang.delete',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'kriteria-saw.view',
            'kriteria-saw.edit',
            'kriteria-saw.delete',
            'penilaian-saw.view',
            'profile-pelanggan.view',
            'profile-pelanggan.edit',
            'profile-pelanggan.delete',
            'roles.view',
            'roles.edit',
            'roles.delete',
        ];

        $dashboard_permissions = [
            // Pelanggan
            'dashbord-menu.my-complaint-total',
            'dashbord-menu.my-complaint-valid',
            'dashbord-menu.my-complaint-invalid',
            'dashbord-menu.my-complaint-complete',

            // Customer Service / Koordinator / Asisten Manager
            'dashbord-menu.complaint-total',
            'dashbord-menu.submission-total',
            'dashbord-menu.complaint-valid',
            'dashbord-menu.complaint-invalid',
            'dashbord-menu.complaint-assigned',
            'dashbord-menu.complaint-complete',
            'dashbord-menu.complaint-checking',
            'dashbord-menu.complaint-working',

            // Teknisi
            'dashbord-menu.technician-complaint-assigned',
            'dashbord-menu.technician-complaint-complete',
            'dashbord-menu.technician-complaint-checking',
            'dashbord-menu.technician-complaint-working',

            // Manager
            'dashbord-menu.all-complaint-total',
            'dashbord-menu.all-submission-total',
            'dashbord-menu.all-complaint-valid',
            'dashbord-menu.all-complaint-invalid',
            'dashbord-menu.all-complaint-assigned',
            'dashbord-menu.all-complaint-complete',
            'dashbord-menu.all-complaint-checking',
            'dashbord-menu.all-complaint-working',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach ($dashboard_permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $pelanggan = Role::firstOrCreate(['name' => 'pelanggan', 'guard_name' => 'web']);
        $pelanggan->syncPermissions([
            'dashbord-menu.my-complaint-total',
            'dashbord-menu.my-complaint-valid',
            'dashbord-menu.my-complaint-invalid',
            'dashbord-menu.my-complaint-complete',
            'pengaduan.view',
            'pengaduan.create',
            'pengaduan.edit',
            'pengaduan.delete',
            'pengerjaan.view',
            'pengerjaan.rating',
            'profile-pelanggan.view',
            'profile-pelanggan.edit',
            'profile-pelanggan.delete',

        ]);

        $customerService = Role::firstOrCreate(['name' => 'customer_service', 'guard_name' => 'web']);
        $customerService->syncPermissions([
            'dashbord-menu.complaint-total',
            'dashbord-menu.submission-total',
            'dashbord-menu.complaint-valid',
            'dashbord-menu.complaint-invalid',
            'dashbord-menu.complaint-assigned',
            'dashbord-menu.complaint-complete',
            'pengaduan.view',
            'pengaduan.validate',
            'pengerjaan.view',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'laporan.view',
            'laporan-pengaduan.view',
            'laporan-pengerjaan.view',
        ]);

        $teknisi = Role::firstOrCreate(['name' => 'teknisi', 'guard_name' => 'web']);
        $teknisi->syncPermissions([
            'dashbord-menu.technician-complaint-assigned',
            'dashbord-menu.technician-complaint-complete',
            'dashbord-menu.technician-complaint-checking',
            'dashbord-menu.technician-complaint-working',
            'pengaduan.view',
            'pengerjaan.view',
            'pengerjaan.edit',
            'laporan.view',
            'laporan-pengerjaan.view',
        ]);



        $koordinatorTeknisi = Role::firstOrCreate(['name' => 'koordinator_teknisi', 'guard_name' => 'web']);
        $koordinatorTeknisi->syncPermissions([
            'dashbord-menu.complaint-total',
            'dashbord-menu.complaint-valid',
            'dashbord-menu.complaint-assigned',
            'dashbord-menu.complaint-complete',
            'dashbord-menu.complaint-checking',
            'dashbord-menu.complaint-working',
            'pengaduan.view',
            'pengaduan.assign-teknisi',
            'pengerjaan.view',
            'penilaian-saw.view',
            'laporan.view',
            'laporan-pengaduan.view',
            'laporan-pengerjaan.view',
        ]);

        $asistenManager = Role::firstOrCreate(['name' => 'asisten_manager', 'guard_name' => 'web']);
        $asistenManager->syncPermissions([
            'dashbord-menu.complaint-total',
            'dashbord-menu.submission-total',
            'dashbord-menu.complaint-valid',
            'dashbord-menu.complaint-invalid',
            'dashbord-menu.complaint-assigned',
            'dashbord-menu.complaint-complete',
            'dashbord-menu.complaint-checking',
            'dashbord-menu.complaint-working',
            'pengaduan.view',
            'pengerjaan.view',
            'laporan.view',
            'laporan-pengaduan.view',
            'laporan-pengerjaan.view',
        ]);

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'dashbord-menu.all-complaint-total',
            'dashbord-menu.all-submission-total',
            'dashbord-menu.all-complaint-valid',
            'dashbord-menu.all-complaint-invalid',
            'dashbord-menu.all-complaint-assigned',
            'dashbord-menu.all-complaint-complete',
            'dashbord-menu.all-complaint-checking',
            'dashbord-menu.all-complaint-working',
            'cabang.view',
            'cabang.create',
            'cabang.edit',
            'cabang.delete',
            'pengaduan.view',
            'pengerjaan.view',
            'laporan.view',
            'laporan-pengaduan.view',
            'laporan-pengerjaan.view',
        ]);
    }
}
