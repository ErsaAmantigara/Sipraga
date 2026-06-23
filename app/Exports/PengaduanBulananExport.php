<?php

namespace App\Exports;

use App\Models\Pengaduan;
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

class PengaduanBulananExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
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
            'Tanggal Pengaduan',
            'Pelanggan',
            'No Id Pelanggan',
            'Cabang',
            'Jenis Keluhan',
            'Deskripsi Keluhan',
            'Stand Meter Terakhir',
            'Status Pengaduan',
            'Tanggal Selesai',
        ];
    }

    public function map($row): array
    {
        $this->nomor++;
        /** @var Pengaduan $row */
        return [
            $this->nomor,
            $row->nomor_pengaduan,
            $row->tanggal_pengaduan?->format('Y-m-d H:i:s') ?? '',
            $row->user->name ?? '',
            $row->user->profilepelanggan->no_id_pelanggan ?? '',
            $row->user->cabang->nama_cabang ?? '',
            $row->jenis_keluhan ?? '',
            $row->deskripsi_keluhan ?? '',
            $row->stand_meter_terakhir ?? '',
            $row->status_pengaduan ?? '',
            $row->tanggal_selesai?->format('Y-m-d H:i:s') ?? '',
        ];
    }

    public function registerEvents(): array
    {
        $period = Carbon::createFromFormat('Y-m', $this->month)->locale('id');
        $periodeLabel = 'PERIODE BULAN ' . strtoupper($period->translatedFormat('F Y'));

        return [
            AfterSheet::class => function (AfterSheet $event) use ($periodeLabel) {
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'PT. SARANA PEMBANGUNAN PALEMBANG JAYA');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', 'UNIT USAHA JARINGAN GAS');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A3:K3');
                $sheet->setCellValue('A3', 'REKAP DATA BULANAN PENGADUAN KELUHAN PELANGGAN');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A4:K4');
                $sheet->setCellValue('A4', $periodeLabel);
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $headingsRange = 'A6:K6';
                $sheet->getStyle($headingsRange)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                $lastRow = $sheet->getHighestRow();
                if ($lastRow >= 7) {
                    $dataRange = 'A7:K' . $lastRow;
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
