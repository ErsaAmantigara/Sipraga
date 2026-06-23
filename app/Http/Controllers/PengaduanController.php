<?php

namespace App\Http\Controllers;

use App\Models\Pengerjaan;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function __construct(
        private readonly NotifikasiController $notifikasiController,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $user = auth()->user();

        $pengaduan = Pengaduan::with(['user.profilepelanggan']);

        $this->applyPengaduanScope($pengaduan, $user);

        $pengaduan = $pengaduan
            ->when($search, function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('nomor_pengaduan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status_pengaduan', $status);
            })
            ->orderBy('tanggal_pengaduan', 'desc')
            ->paginate(10);

        return view('pengaduan.index', compact('pengaduan', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisKeluhanList = [
            'Pipa Bocor',
            'Pipa Service',
            'Rekening Tagihan',
            'Meter Gas Rusak',
            'Kompor Tidak Hidup',
            'Api Kompor Kecil',
            'Perubahan Instalasi Pipa',
            'Perubahan Instalasi Meteran',
            'Penambahan Titik Api',
            'Instalasi Kompor Baru',
            'Cabut Meteran (Stop Berlangganan)',
        ];

        return view('pengaduan.create', compact('jenisKeluhanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_keluhan' => 'required|in:Pipa Bocor,Pipa Service,Rekening Tagihan,Meter Gas Rusak,Kompor Tidak Hidup,Api Kompor Kecil,Perubahan Instalasi Pipa,Perubahan Instalasi Meteran,Penambahan Titik Api,Instalasi Kompor Baru,Cabut Meteran (Stop Berlangganan)',
            'deskripsi_keluhan' => 'required|string',
            'stand_meter_terakhir' => 'required|integer|min:0',
            'foto_keluhan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = auth()->user()->user_id;
        $validated['nomor_pengaduan'] = $this->generateNomorPengaduan();
        $validated['tanggal_pengaduan'] = now();
        $validated['status_pengaduan'] = 'pengajuan';

        if ($request->hasFile('foto_keluhan')) {
            $file = $request->file('foto_keluhan');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('pengaduan', $filename, 'public');
            $validated['foto_keluhan'] = $filename;
        }

        $pengaduan = Pengaduan::create($validated);

        $pengaduan->loadMissing(['user.cabang']);

        $this->notifikasiController->sendToRoleInCabang(
            $pengaduan->user->cabang_id,
            'customer_service',
            "🔔 *PENGADUAN BARU*\n
            📄 No. Pengaduan: {$pengaduan->nomor_pengaduan}\n
            👤 Pelanggan: {$pengaduan->user?->name}\n
            🏢 Cabang: {$pengaduan->user?->cabang?->nama_cabang}\n
            📋 Jenis Keluhan: {$pengaduan->jenis_keluhan}\n
            Segera lakukan verifikasi di sistem.",
        );

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil disubmit');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        $this->authorizePengaduanOwner($pengaduan);

        $pengaduan->load([
            'user',
            'penilaianSaw',
        ]);

        $cabangId = $pengaduan->user?->cabang_id;

        $teknisi = $this->availableTeknisiQuery($cabangId)
            ->orderBy('name')
            ->get();

        return view('pengaduan.show', compact('pengaduan', 'teknisi'));
    }

    /**
     * Validate pengaduan (CS action)
     */
    public function validasi(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status_pengaduan' => 'required|in:valid,tidak_valid',
            'keterangan_cs' => 'required_if:status_pengaduan,tidak_valid',
        ]);

        $pengaduan->update($validated);

        if ($validated['status_pengaduan'] === 'valid') {
            $pengaduan->loadMissing(['user.cabang']);

            $this->notifikasiController->sendToRoleInCabang(
                $pengaduan->user->cabang_id,
                'koordinator_teknisi',
                "✅ PENGADUAN VALID SIAP DITINDAKLANJUTI\n
                📄 No. Pengaduan: {$pengaduan->nomor_pengaduan}\n
                👤 Pelanggan: {$pengaduan->user?->name}\n
                🏢 Cabang: {$pengaduan->user?->cabang?->nama_cabang}\n
                📋 Jenis Keluhan: {$pengaduan->jenis_keluhan}\n
                Silakan tentukan teknisi pada sistem.",
            );
        } else {
            $this->clearSawScoreIfStatusIsNotValid($pengaduan);
        }

        return redirect()->route('pengaduan.show', $pengaduan->pengaduan_id)
            ->with('success', 'Pengaduan berhasil divalidasi');
    }

    /**
     * Assign teknisi to pengaduan
     */
    public function assignTeknisi(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);

        if (! $pengaduan->penilaianSaw()->exists()) {
            return redirect()->route('pengaduan.show', $pengaduan->pengaduan_id)
                ->with('error', 'Pengaduan harus melalui penilaian SAW sebelum teknisi dapat ditugaskan.');
        }

        $teknisi = User::role('teknisi')
            ->whereKey($validated['user_id'])
            ->firstOrFail();

        $this->authorizeCabangMatch($teknisi->cabang_id);

        $teknisiTersedia = $this->availableTeknisiQuery($teknisi->cabang_id)
            ->whereKey($teknisi->user_id)
            ->exists();

        if (! $teknisiTersedia) {
            return redirect()->route('pengaduan.show', $pengaduan->pengaduan_id)
                ->with('error', 'Teknisi yang dipilih sedang bertugas atau tidak aktif.');
        }

        $pengaduan->update([
            'status_pengaduan' => 'teknisi_ditugaskan',
        ]);
        $this->clearSawScoreIfStatusIsNotValid($pengaduan);

        Pengerjaan::updateOrCreate(
            ['pengaduan_id' => $pengaduan->pengaduan_id],
            [
                'user_id' => $teknisi->user_id,
                'status_pengerjaan' => null,
                'tanggal_mulai' => null,
            ]
        );

        $this->notifikasiController->sendWhatsApp(
            $teknisi,
            "🛠️ TUGAS PENGADUAN BARU\n
            Halo, Anda telah mendapatkan penugasan baru.
            📄 No. Pengaduan: {$pengaduan->nomor_pengaduan}\n
            👤 Pelanggan: {$pengaduan->user?->name}\n
            📱 No. HP: {$pengaduan->user->no_hp}\n
            🏢 Cabang: {$pengaduan->user?->cabang?->nama_cabang}\n
            📋 Jenis Keluhan:{$pengaduan->jenis_keluhan} \n
            📱 Silakan login ke sistem untuk melihat detail pekerjaan dan melakukan penanganan.",
        );

        return redirect()->route('pengerjaan.index')
            ->with('success', 'Teknisi berhasil ditugaskan');
    }

    /**
     * Generate nomor pengaduan
     */
    private function generateNomorPengaduan(): string
    {
        $prefix = 'PGD';
        $user = auth()->user();
        $cabang = strtoupper($user->cabang->nama_cabang);

        $lastPengaduan = Pengaduan::where('nomor_pengaduan', 'like', "{$prefix}-{$cabang}-%")
            ->orderBy('nomor_pengaduan', 'desc')
            ->first();

        if ($lastPengaduan) {
            $lastNumber = (int) substr($lastPengaduan->nomor_pengaduan, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "{$prefix}-{$cabang}-{$newNumber}";
    }

    private function authorizePengaduanOwner(Pengaduan $pengaduan): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan') && $pengaduan->user_id === $user->user_id) {
            return;
        }

        if ($user->hasRole('teknisi') && $pengaduan->pengerjaan?->user_id === $user->user_id) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $pengaduan->user?->cabang_id === $user->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
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

    private function authorizeCabangMatch(?int $targetCabangId): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $targetCabangId === $user->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak dapat memilih data di luar cabang Anda.');
    }

    private function hasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'manager']);
    }

    private function hasCabangScopedAccess(User $user): bool
    {
        return $user->hasAnyRole(['customer_service', 'koordinator_teknisi', 'asisten_manager']);
    }

    private function clearSawScoreIfStatusIsNotValid(Pengaduan $pengaduan): void
    {
        if ($pengaduan->status_pengaduan === 'valid') {
            return;
        }

        $pengaduan->penilaianSaw()->delete();
    }

    private function availableTeknisiQuery(?int $cabangId)
    {
        return User::role('teknisi')
            ->where('is_active', true)
            ->where('cabang_id', $cabangId)
            ->whereDoesntHave('pengerjaan', function ($query) {
                $query->where(function ($statusQuery) {
                    $statusQuery->whereNull('status_pengerjaan')
                        ->orWhere('status_pengerjaan', '!=', 'selesai');
                });
            });
    }
}
