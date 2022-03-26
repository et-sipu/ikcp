<?php

namespace App\Exports;

use App\Models\Vessel;
use App\Models\Version;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class VesselCertificatesExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use RegistersEventListeners;

    private static $count;

    /**
     * @var Vessel
     */
    private $vessel;

    /**
     * BiographiesExport constructor.
     *
     * @param $count
     * @param Vessel $vessel
     */
    public function __construct($count, Vessel $vessel)
    {
        self::$count = $count;
        $this->vessel = $vessel;
    }

    public function map($certificate): array
    {
        return [
            'INDEX NR'           => $certificate->id,
            'CERTIFICATE'        => $certificate->document_type,
            'CERTIFICATE NUMBER' => $certificate->document_no,
            'VALID UNTIL'        => $certificate->document_expiry_date,
            'REMARKS'            => $certificate->remarks,
        ];
    }

    public function headings(): array
    {
        return [
            'INDEX NR',
            'CERTIFICATE',
            'CERTIFICATE NUMBER',
            'VALID UNTIL',
            'REMARKS',
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(40);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(25);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);

        $event->sheet->getDelegate()->getStyle('A1:E'.(self::$count + 1))->applyFromArray(
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]
        );
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->vessel->documents;
    }
}
