<?php

namespace App\Http\Controllers;

use App\Models\KriteriaSaw;
use App\Models\PenilaianSaw;
use App\Models\Pengaduan;
use App\Models\Pengerjaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $pengaduanBase = Pengaduan::query()->with(['user.cabang']);
        $this->applyPengaduanScope($pengaduanBase, $user);

        $pengerjaanBase = Pengerjaan::query()->with(['pengaduan.user.cabang', 'teknisi']);
        $this->applyPengerjaanScope($pengerjaanBase, $user);

        $priorityBase = PenilaianSaw::query()->with(['pengaduan.user.cabang']);
        $this->applyPriorityScope($priorityBase, $user);

        $status = [
            'total_pengaduan' => (clone $pengaduanBase)->count(),
            'pengajuan' => (clone $pengaduanBase)->where('status_pengaduan', 'pengajuan')->count(),
            'valid' => (clone $pengaduanBase)->where('status_pengaduan', 'valid')->count(),
            'teknisi_ditugaskan' => (clone $pengaduanBase)->where('status_pengaduan', 'teknisi_ditugaskan')->count(),
            'selesai' => (clone $pengaduanBase)->where('status_pengaduan', 'selesai')->count(),
            'tidak_valid' => (clone $pengaduanBase)->where('status_pengaduan', 'tidak_valid')->count(),
            'belum_ada_status' => (clone $pengerjaanBase)->whereNull('status_pengerjaan')->count(),
            'dalam_pengecekan' => (clone $pengerjaanBase)->where('status_pengerjaan', 'dalam_pengecekan')->count(),
            'dalam_pengerjaan' => (clone $pengerjaanBase)->where('status_pengerjaan', 'dalam_pengerjaan')->count(),
            'pengerjaan_selesai' => (clone $pengerjaanBase)->where('status_pengerjaan', 'selesai')->count(),
        ];

        $recentPengaduan = (clone $pengaduanBase)
            ->orderByDesc('tanggal_pengaduan')
            ->limit(10)
            ->get();

        $recentPengerjaan = (clone $pengerjaanBase)
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();


        return view('dashboard', [
            'status' => $status,
            'recentPengaduan' => $recentPengaduan,
            'recentPengerjaan' => $recentPengerjaan,

        ]);
    }

    private function applyPengaduanScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan')) {
            $query->where('user_id', $user->user_id);
            return;
        }

        if ($user->hasRole('teknisi')) {
            $query->whereHas('pengerjaan', function ($pengerjaanQuery) use ($user) {
                $pengerjaanQuery->where('user_id', $user->user_id);
            });
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->whereHas('user', function ($userQuery) use ($user) {
                $userQuery->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function applyPengerjaanScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan')) {
            $query->whereHas('pengaduan', function ($pengaduanQuery) use ($user) {
                $pengaduanQuery->where('user_id', $user->user_id);
            });
            return;
        }

        if ($user->hasRole('teknisi')) {
            $query->where('user_id', $user->user_id);
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->whereHas('pengaduan.user', function ($userQuery) use ($user) {
                $userQuery->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function applyPriorityScope(Builder $query, User $user): void
    {
        $query->whereHas('pengaduan', function ($pengaduanQuery) {
            $pengaduanQuery->where('status_pengaduan', 'valid');
        });

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan')) {
            $query->whereHas('pengaduan', function ($pengaduanQuery) use ($user) {
                $pengaduanQuery->where('user_id', $user->user_id);
            });
            return;
        }

        if ($user->hasRole('teknisi')) {
            $query->whereHas('pengaduan.pengerjaan', function ($pengerjaanQuery) use ($user) {
                $pengerjaanQuery->where('user_id', $user->user_id);
            });
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->whereHas('pengaduan.user', function ($userQuery) use ($user) {
                $userQuery->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function hasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'manager']);
    }

    private function hasCabangScopedAccess(User $user): bool
    {
        return $user->hasAnyRole(['customer_service', 'koordinator_teknisi', 'asisten_manager']);
    }
}
