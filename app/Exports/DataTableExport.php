<?php

namespace App\Exports;

use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

//class DataTableExport implements FromCollection, WithHeadings
class DataTableExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;

    protected $headings;

    protected $query;

    protected $columns;

    private static $count = 0;

    private static $headings_count = 0;

    public function __construct(array $headings, Builder $query, array $columns, $data, $count)
    {
        $this->headings = $headings;
        $this->query = $query;
        $this->columns = $columns;
        $this->data = $data;
        self::$count = $count;
        self::$headings_count = \count($headings ? $headings : array_keys($data[0]));
    }

    public function headings(): array
    {
        $headings = $this->headings ?: array_keys($this->data[0]);
        $headings = array_map(function ($value) {
            return Lang::has('validation.attributes.'.$value) ? __('validation.attributes.'.$value) : $value;
        }, $headings);

        return $headings;
    }

    public function collection()
    {
        return $this->query->get($this->columns)->each(function (Model $item) {
            return $item->setAppends([]);
        });
    }

    public function array(): array
    {
        return $this->data;
    }

    protected static function lastColumnLetter()
    {
        return self::columnLetter(self::$headings_count);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getDelegate()->getStyle('A1:'.self::lastColumnLetter().'1')->applyFromArray(
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

        $event->sheet->getDelegate()->getStyle('A1:'.self::lastColumnLetter().(self::$count + 1))->applyFromArray(
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

    protected static function columnLetter($c)
    {
        $c = (int) $c;
        if ($c <= 0) {
            return '';
        }

        $letter = '';

        while (0 !== $c) {
            $p = ($c - 1) % 26;
            $c = (int) (($c - $p) / 26);
            $letter = \chr(65 + $p).$letter;
        }

        return $letter;
    }
}
