<?php

namespace App\Http\Controllers;

use App\Models\KriteriaSaw;
use App\Models\PenilaianSaw;
use App\Models\Pengaduan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PenilaianSawController extends Controller
{
    public function index(Request $request)
    {
        $kriteria = KriteriaSaw::orderBy('kriteria_saw_id', 'asc')
            ->get()
            ->values();

        $fieldMap = $this->fieldMap($kriteria);

        $pengaduan = Pengaduan::with(['user.cabang', 'penilaianSaw'])
            ->where('status_pengaduan', 'valid')
            ->orderBy('tanggal_pengaduan', 'asc');

        $this->applyUserScope($pengaduan, auth()->user());

        $pengaduan = $pengaduan->paginate(30);

        $subKriteriaLabelMap = $this->getSubKriteriaLabelMap();

        $pengaduan->getCollection()->transform(function (Pengaduan $item) use (
            $fieldMap,
            $subKriteriaLabelMap
        ) {
            $nilaiSaw = $item->penilaianSaw;

            $criteriaSummary = collect($fieldMap)->map(function (
                array $config
            ) use (
                $nilaiSaw,
                $subKriteriaLabelMap
            ) {
                $numericValue = $nilaiSaw?->{$config['field']};

                $kode = $config['kriteria']->kode_kriteria;

                $subLabel = $subKriteriaLabelMap[$kode][(int) $numericValue] ?? null;

                return [
                    'kode'      => $kode,
                    'label'     => $config['kriteria']->nama_kriteria,
                    'value'     => $numericValue,
                    'sub_label' => $subLabel,
                    'bobot'     => $config['kriteria']->bobot,
                    'jenis'     => $config['kriteria']->jenis,
                ];
            })->values();

            $item->criteria_summary = $criteriaSummary;

            return $item;
        });

        $rankedRows = $pengaduan->getCollection()
            ->filter(fn(Pengaduan $item) => $item->penilaianSaw)
            ->sort(function (Pengaduan $left, Pengaduan $right) {
                $leftRanking = $left->penilaianSaw->ranking ?: PHP_INT_MAX;
                $rightRanking = $right->penilaianSaw->ranking ?: PHP_INT_MAX;

                if ($leftRanking !== $rightRanking) {
                    return $leftRanking <=> $rightRanking;
                }

                return (float) $right->penilaianSaw->nilai_preferensi
                    <=> (float) $left->penilaianSaw->nilai_preferensi;
            })
            ->values();

        $normalisasiRows = $pengaduan->getCollection()
            ->filter(fn(Pengaduan $item) => $item->penilaianSaw)
            ->values();

        return view('penilaian-saw.index', compact(
            'pengaduan',
            'kriteria',
            'fieldMap',
            'rankedRows',
            'normalisasiRows'
        ));
    }

    public function generate(): RedirectResponse
    {
        $pengaduan = Pengaduan::where('status_pengaduan', 'valid');

        $this->applyUserScope($pengaduan, auth()->user());

        $pengaduanIds = $pengaduan->pluck('pengaduan_id');

        PenilaianSaw::whereIn('pengaduan_id', $pengaduanIds)->delete();

        $this->processAll(true);

        return redirect()
            ->route('penilaian-saw.index')
            ->with('success', 'Penilaian SAW berhasil digenerate ulang.');
    }

    public function processAll(bool $force = false): void
    {
        $pengaduans = Pengaduan::with([
            'user.profilePelanggan',
            'user.cabang',
            'penilaianSaw'
        ])
            ->where('status_pengaduan', 'valid');

        if (auth()->check()) {
            $this->applyUserScope($pengaduans, auth()->user());
        }

        $pengaduans = $pengaduans->get();

        foreach ($pengaduans as $pengaduan) {
            if ($force || !$pengaduan->penilaianSaw) {
                $data = $this->autoCalculateNilai($pengaduan);

                PenilaianSaw::create($data);
            }
        }

        $this->recalculateScores();
    }

    private function autoCalculateNilai(Pengaduan $pengaduan): array
    {
        $tanggalPengaduan = Carbon::parse($pengaduan->tanggal_pengaduan);

        $hours = $tanggalPengaduan->diffInHours(now());

        $pelanggan = $pengaduan->user?->profilePelanggan;
        $cabang = $pengaduan->user?->cabang;

        $distance = $this->calculateDistance($cabang, $pelanggan);

        $data = [
            'pengaduan_id' => $pengaduan->pengaduan_id
        ];

        $kriteria = KriteriaSaw::orderBy('kriteria_saw_id')->get();

        foreach ($kriteria as $k) {
            $field = $this->getFieldForKode($k->kode_kriteria);

            if (!$field) {
                continue;
            }

            $data[$field] = match ($k->kode_kriteria) {
                'C1' => $this->calculateC1($pengaduan),
                'C2' => $this->calculateC2($hours),
                'C3' => $this->calculateC3($pelanggan),
                'C4' => $this->calculateC4($distance),
                default => 0,
            };
        }

        return $data;
    }

    private function getFieldForKode(string $kode): ?string
    {
        return match ($kode) {
            'C1' => 'c1_tingkat_urgensi',
            'C2' => 'c2_lama_waktu_pelaporan',
            'C3' => 'c3_jenis_pelanggan',
            'C4' => 'c4_jarak_kelokasi',
            default => null,
        };
    }

    private function calculateC1(Pengaduan $pengaduan): int
    {
        return match ($pengaduan->jenis_keluhan) {
            'Pipa Bocor' => 4,
            'Pipa Service',
            'Rekening Tagihan' => 3,
            'Meter Gas Rusak',
            'Kompor Tidak Hidup',
            'Api Kompor Kecil' => 2,
            default => 1,
        };
    }

    private function calculateC2(float $hours): int
    {
        return $hours > 8
            ? 4
            : ($hours > 6
                ? 3
                : ($hours > 3
                    ? 2
                    : 1));
    }

    private function calculateC3($pelanggan): int
    {
        return match ($pelanggan?->jenis_pelanggan) {
            'PK2' => 4,
            'PK1' => 3,
            'R2' => 2,
            default => 1,
        };
    }

    private function calculateC4(float $distance): int
    {
        return match (true) {
            $distance > 6 => 4,
            $distance > 4 => 3,
            $distance > 2 => 2,
            default => 1,
        };
    }

    private function calculateDistance($cabang, $pelanggan): float
    {
        if (
            !$cabang ||
            !$pelanggan ||
            !$pelanggan->latitude ||
            !$pelanggan->longitude
        ) {
            return 0;
        }

        return $this->haversineKm(
            (float) $cabang->latitude,
            (float) $cabang->longitude,
            (float) $pelanggan->latitude,
            (float) $pelanggan->longitude,
        );
    }

    private function fieldMap(Collection $kriteria): array
    {
        $kodeToField = [
            'C1' => [
                'field' => 'c1_tingkat_urgensi',
                'normalisasi' => 'normalisasi_c1'
            ],
            'C2' => [
                'field' => 'c2_lama_waktu_pelaporan',
                'normalisasi' => 'normalisasi_c2'
            ],
            'C3' => [
                'field' => 'c3_jenis_pelanggan',
                'normalisasi' => 'normalisasi_c3'
            ],
            'C4' => [
                'field' => 'c4_jarak_kelokasi',
                'normalisasi' => 'normalisasi_c4'
            ],
        ];

        return $kriteria->map(function (KriteriaSaw $item) use ($kodeToField) {
            $mapping = $kodeToField[$item->kode_kriteria] ?? null;

            if (!$mapping) {
                return null;
            }

            return [
                'field' => $mapping['field'],
                'kriteria' => $item,
                'normalisasi_field' => $mapping['normalisasi'],
            ];
        })
            ->filter()
            ->values()
            ->all();
    }

    private function getSubKriteriaLabelMap(): array
    {
        return [
            'C1' => [
                4 => 'Sangat Tinggi',
                3 => 'Tinggi',
                2 => 'Sedang',
                1 => 'Rendah'
            ],
            'C2' => [
                4 => 'Sangat Lama',
                3 => 'Lama',
                2 => 'Sedang',
                1 => 'Baru'
            ],
            'C3' => [
                4 => 'PK2',
                3 => 'PK1',
                2 => 'R2',
                1 => 'R1'
            ],
            'C4' => [
                4 => 'Jauh',
                3 => 'Sedang',
                2 => 'Dekat',
                1 => 'Sangat Dekat'
            ],
        ];
    }

    private function applyUserScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if (
            $this->hasCabangScopedAccess($user)
            && $user->cabang_id
        ) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function hasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole([
            'super-admin',
            'manager'
        ]);
    }

    private function hasCabangScopedAccess(User $user): bool
    {
        return $user->hasAnyRole([
            'customer_service',
            'koordinator_teknisi',
            'asisten_manager'
        ]);
    }

    private function recalculateScores(): void
    {
        $kriteria = KriteriaSaw::orderBy('kriteria_saw_id', 'asc')->get();

        if ($kriteria->isEmpty()) {
            return;
        }

        $kodeToField = [
            'C1' => [
                'nilai' => 'c1_tingkat_urgensi',
                'normalisasi' => 'normalisasi_c1'
            ],
            'C2' => [
                'nilai' => 'c2_lama_waktu_pelaporan',
                'normalisasi' => 'normalisasi_c2'
            ],
            'C3' => [
                'nilai' => 'c3_jenis_pelanggan',
                'normalisasi' => 'normalisasi_c3'
            ],
            'C4' => [
                'nilai' => 'c4_jarak_kelokasi',
                'normalisasi' => 'normalisasi_c4'
            ],
        ];

        $fieldMap = [];

        foreach ($kriteria as $item) {
            if (isset($kodeToField[$item->kode_kriteria])) {
                $fieldMap[$item->kode_kriteria] = $kodeToField[$item->kode_kriteria];
            }
        }

        if (empty($fieldMap)) {
            return;
        }

        $cabangIds = Pengaduan::query()
            ->where('status_pengaduan', 'valid')
            ->join('users', 'users.user_id', '=', 'pengaduan.user_id')
            ->whereNotNull('users.cabang_id')
            ->distinct()
            ->pluck('users.cabang_id');

        if ($cabangIds->isEmpty()) {
            return;
        }

        $totalBobot = (float) $kriteria->sum('bobot');

        if ($totalBobot <= 0) {
            return;
        }

        foreach ($cabangIds as $cabangId) {

            $rows = PenilaianSaw::whereHas(
                'pengaduan.user',
                function ($q) use ($cabangId) {
                    $q->where('cabang_id', $cabangId);
                }
            )->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $maxValues = [];
            $minValues = [];

            foreach ($kriteria as $item) {
                $kode = $item->kode_kriteria;

                if (!isset($fieldMap[$kode])) {
                    continue;
                }

                $field = $fieldMap[$kode]['nilai'];

                $values = $rows->pluck($field)
                    ->map(fn($v) => (float) ($v ?? 0));

                if ($item->jenis === 'benefit') {
                    $maxValues[$kode] = $values->max();
                } else {
                    $minValues[$kode] = $values->min();
                }
            }

            $calculated = $rows->map(function (
                PenilaianSaw $row
            ) use (
                $kriteria,
                $fieldMap,
                $totalBobot,
                $maxValues,
                $minValues
            ) {
                $normalisasi = [];

                $nilaiPreferensi = 0;

                foreach ($kriteria as $item) {
                    $kode = $item->kode_kriteria;

                    if (!isset($fieldMap[$kode])) {
                        continue;
                    }

                    $field = $fieldMap[$kode]['nilai'];

                    $normalisasiField = $fieldMap[$kode]['normalisasi'];

                    $nilai = (float) ($row->$field ?? 0);

                    if ($item->jenis === 'benefit') {
                        $maxVal = $maxValues[$kode] ?? 0;

                        $normalizedValue = $maxVal > 0
                            ? $nilai / $maxVal
                            : 0;
                    } else {
                        $minVal = $minValues[$kode] ?? 0;

                        $normalizedValue = $nilai > 0 && $minVal > 0
                            ? $minVal / $nilai
                            : 0;
                    }

                    $normalisasi[$normalisasiField] = round(
                        $normalizedValue,
                        4
                    );

                    $nilaiPreferensi += $normalizedValue
                        * (((float) $item->bobot) / $totalBobot);
                }

                return [
                    'model' => $row,
                    'nilai_preferensi' => round($nilaiPreferensi, 4),
                    'normalisasi' => $normalisasi,
                ];
            })
                ->sortByDesc('nilai_preferensi')
                ->values();

            $calculated->each(function (array $item, int $index) {
                $model = $item['model'];

                $nilaiPreferensi = $item['nilai_preferensi'];

                $updateData = [
                    'nilai_preferensi' => $nilaiPreferensi,
                    'ranking' => $index + 1,
                    'kategori_prioritas' => $this->resolveKategoriPrioritas(
                        $nilaiPreferensi
                    ),
                ];

                foreach ($item['normalisasi'] as $field => $value) {
                    $updateData[$field] = $value;
                }

                $model->update($updateData);
            });
        }
    }

    private function resolveKategoriPrioritas(
        float $nilaiPreferensi
    ): string {
        if ($nilaiPreferensi > 0.8) {
            return 'Sangat Tinggi';
        }

        if ($nilaiPreferensi > 0.6) {
            return 'Tinggi';
        }

        if ($nilaiPreferensi > 0.4) {
            return 'Sedang';
        }

        return 'Rendah';
    }

    private function haversineKm(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($dLon / 2)
            * sin($dLon / 2);

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }
}
