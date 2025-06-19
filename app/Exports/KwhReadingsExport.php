<?php

namespace App\Exports;

use App\Models\KwhReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class KwhReadingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $year;
    protected $month;
    protected $rowNumber = 0;

    public function __construct($year = null, $month = null)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $query = KwhReading::with('panel');
        
        if ($this->year) {
            $query->whereYear('created_at', $this->year);
        }
        
        if ($this->month) {
            $query->whereMonth('created_at', $this->month);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Panel', // Diubah dari 'Panel' ke 'No Panel'
            'Nomor KWH',
            'Catatan',
            'Tanggal Dibuat',
            'Dicatat oleh',
            'Gambar'
        ];
    }

    public function map($reading): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber, // Nomor urut
            $reading->panel ? $reading->panel->no_app : '-', // Hanya tampilkan no panel
            $reading->kwh_number,
            $reading->notes,
            $reading->created_at->format('d F Y'),
            $reading->user ? $reading->user->name : 'Tidak diketahui',
            $reading->image_path ? '[IMAGE]' : 'Tidak ada gambar' // Penanda untuk gambar
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(15); // No Panel
        $sheet->getColumnDimension('C')->setWidth(15); // Nomor KWH
        $sheet->getColumnDimension('D')->setWidth(30); // Catatan
        $sheet->getColumnDimension('E')->setWidth(20); // Tanggal
        $sheet->getColumnDimension('F')->setWidth(20); // Gambar
        $sheet->getColumnDimension('G')->setWidth(30); // Gambar

        return [
            // Header bold dan center
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Alignment untuk semua cell
            'A:F' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            // Border untuk semua cell
            'A1:F' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();
                $rows = $this->collection();
                
                foreach ($rows as $index => $row) {
                    $rowNumber = $index + 2; 
                    
                    // Sisipkan gambar jika ada
                    if ($row->image_path) {
                        $imagePath = storage_path('app/public/' . $row->image_path);
                        
                        if (file_exists($imagePath)) {
                            $drawing = new Drawing();
                            $drawing->setName('KWH Image');
                            $drawing->setDescription('Gambar KWH Meter');
                            $drawing->setPath($imagePath);
                            $drawing->setHeight(80); // Tinggi gambar
                            $drawing->setCoordinates('G' . $rowNumber); // Kolom G
                            $drawing->setOffsetX(5);
                            $drawing->setOffsetY(5);
                            $drawing->setWorksheet($worksheet);
                            
                            // Sesuaikan tinggi baris
                            $worksheet->getRowDimension($rowNumber)->setRowHeight(60);
                        }
                    }
                }
            },
        ];
    }
}