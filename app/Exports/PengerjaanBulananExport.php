<?php

namespace App\Exports;

use App\Models\Pengerjaan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class PengerjaanBulananExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    private int $nomor = 0;

    public function __construct(
        private readonly Collection $rows,
        private readonly string $month,
        private readonly ?string $mengetahui = null,
        private readonly ?string $diperiksa = null,
        private readonly bool $showSignature = true,
    ) {
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Pengaduan',
            'Teknisi',
            'Pelanggan',
            'No Id Pelanggan',
            'Cabang',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status Pengerjaan',
            'Material',
            'Keterangan Teknisi',
            'Rating',
            'Komentar Rating',
        ];
    }

    public function map($row): array
    {
        $this->nomor++;
        /** @var Pengerjaan $row */
        return [
            $this->nomor,
            $row->pengaduan->nomor_pengaduan ?? '',
            $row->teknisi->name ?? '',
            $row->pengaduan->user->name ?? '',
            $row->pengaduan->user->profilepelanggan->no_id_pelanggan ?? '',
            $row->pengaduan->user->cabang->nama_cabang ?? '',
            $row->tanggal_mulai?->format('Y-m-d H:i:s') ?? '',
            $row->tanggal_selesai?->format('Y-m-d H:i:s') ?? '',
            $row->status_pengerjaan ?? 'belum_ada_status',
            $row->material ?? '',
            $row->keterangan_teknisi ?? '',
            $row->rating_nilai ?? '',
            $row->rating_komentar ?? '',
        ];
    }

    public function registerEvents(): array
    {
        $period = Carbon::createFromFormat('Y-m', $this->month)->locale('id');
        $periodeLabel = 'PERIODE BULAN ' . strtoupper($period->translatedFormat('F Y'));

        return [
            AfterSheet::class => function (AfterSheet $event) use ($periodeLabel) {
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', 'PT. SARANA PEMBANGUNAN PALEMBANG JAYA');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A2:M2');
                $sheet->setCellValue('A2', 'UNIT USAHA JARINGAN GAS');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A3:M3');
                $sheet->setCellValue('A3', 'REKAP DATA BULANAN PENGERJAAN TEKNISI');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A4:M4');
                $sheet->setCellValue('A4', $periodeLabel);
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $headingsRange = 'A6:M6';
                $sheet->getStyle($headingsRange)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                $lastRow = $sheet->getHighestRow();
                if ($lastRow >= 7) {
                    $dataRange = 'A7:M' . $lastRow;
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                    ]);
                }

                if ($this->showSignature) {
                    $lastRow = $sheet->getHighestRow();
                    $signRow = $lastRow + 3;

                    $sheet->setCellValue("A{$signRow}", 'Mengetahui,');
                    $sheet->getStyle("A{$signRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("H{$signRow}", 'Diperiksa,');
                    $sheet->getStyle("H{$signRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("A" . ($signRow + 1), 'ASISTEN MANAGER');
                    $sheet->getStyle("A" . ($signRow + 1))->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("H" . ($signRow + 1), 'KOORDINATOR TEKNISI');
                    $sheet->getStyle("H" . ($signRow + 1))->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("A" . ($signRow + 3), '(              )');
                    $sheet->getStyle("A" . ($signRow + 3))->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("H" . ($signRow + 3), '(              )');
                    $sheet->getStyle("H" . ($signRow + 3))->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("A" . ($signRow + 4), $this->mengetahui ?? '........................');
                    $sheet->getStyle("A" . ($signRow + 4))->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("H" . ($signRow + 4), $this->diperiksa ?? '........................');
                    $sheet->getStyle("H" . ($signRow + 4))->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }
            },
        ];
    }
}
