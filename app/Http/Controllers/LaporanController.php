<?php

namespace App\Http\Controllers;

use App\Exports\PengerjaanBulananExport;
use App\Exports\PengaduanBulananExport;
use App\Models\Pengaduan;
use App\Models\Pengerjaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $this->checkPermission('laporan.view');

        $user = auth()->user();
        $month = $request->input('month', now()->format('Y-m'));
        $period = $this->resolveMonth($month);

        $pengaduanQuery = Pengaduan::query()->with(['user.cabang']);
        $this->applyPengaduanScope($pengaduanQuery, $user);
        $pengaduanQuery
            ->where('status_pengaduan', 'selesai')
            ->whereBetween('tanggal_pengaduan', [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()]);

        $pengerjaanQuery = Pengerjaan::query()->with(['pengaduan.user.cabang', 'teknisi']);
        $this->applyPengerjaanScope($pengerjaanQuery, $user);
        $pengerjaanQuery
            ->where('status_pengerjaan', 'selesai')
            ->where(function ($query) use ($period) {
            $query->whereBetween('created_at', [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()])
                ->orWhereBetween('updated_at', [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()]);
        });

        return view('laporan.index', [
            'month' => $month,
            'scopeLabel' => $this->resolveScopeLabel($user),
            'pengaduanCount' => (clone $pengaduanQuery)->count(),
            'pengerjaanCount' => (clone $pengerjaanQuery)->count(),
        ]);
    }

    public function pengaduan(Request $request): BinaryFileResponse
    {
        $this->checkPermission('laporan.view');

        $user = auth()->user();
        $month = $request->input('month', now()->format('Y-m'));
        $period = $this->resolveMonth($month);

        $query = Pengaduan::query()
            ->with(['user.cabang', 'user.profilepelanggan'])
            ->where('status_pengaduan', 'selesai')
            ->whereBetween('tanggal_pengaduan', [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()])
            ->orderBy('tanggal_pengaduan');

        $this->applyPengaduanScope($query, $user);

        $rows = $query->get();

        [$mengetahui, $diperiksa] = $this->resolvePejabat($user, $rows);
        $showSignature = !$this->hasGlobalAccess($user);

        return Excel::download(
            new PengaduanBulananExport($rows, $month, $mengetahui, $diperiksa, $showSignature),
            "laporan-pengaduan-{$month}.xlsx"
        );
    }

    public function pengerjaan(Request $request): BinaryFileResponse
    {
        $this->checkPermission('laporan.view');

        $user = auth()->user();
        $month = $request->input('month', now()->format('Y-m'));
        $period = $this->resolveMonth($month);

        $query = Pengerjaan::query()
            ->with(['pengaduan.user.cabang', 'pengaduan.user.profilepelanggan', 'teknisi'])
            ->where('status_pengerjaan', 'selesai')
            ->where(function ($builder) use ($period) {
                $builder->whereBetween('created_at', [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()])
                    ->orWhereBetween('updated_at', [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()]);
            })
            ->orderBy('updated_at');

        $this->applyPengerjaanScope($query, $user);

        $rows = $query->get();

        [$mengetahui, $diperiksa] = $this->resolvePejabat($user, $rows);
        $showSignature = !$this->hasGlobalAccess($user);

        return Excel::download(
            new PengerjaanBulananExport($rows, $month, $mengetahui, $diperiksa, $showSignature),
            "laporan-pengerjaan-{$month}.xlsx"
        );
    }

    private function resolveMonth(string $month): Carbon
    {
        try {
            return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable $exception) {
            abort(422, 'Format bulan tidak valid.');
        }
    }

    private function applyPengaduanScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
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

    private function resolveScopeLabel(User $user): string
    {
        if ($this->hasCabangScopedAccess($user)) {
            return 'Cabang ' . ($user->cabang?->nama_cabang ?? '-');
        }

        return 'Semua Cabang';
    }

    private function hasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'manager']);
    }

    private function hasCabangScopedAccess(User $user): bool
    {
        return $user->hasAnyRole(['asisten_manager', 'koordinator_teknisi', 'customer_service', 'teknisi']);
    }

    private function resolvePejabat(User $user, Collection $rows): array
    {
        if ($this->hasGlobalAccess($user)) {
            return [null, null];
        }

        $cabangId = $user->cabang_id;

        $asmen = User::role('asisten_manager')
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->first();

        $koordinator = User::role('koordinator_teknisi')
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->first();

        return [$asmen?->name, $koordinator?->name];
    }
}
