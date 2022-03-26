<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Attendance;
use App\Models\Fingerprint;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

//HeadingRowFormatter::default('none');

//class FingerprintsImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
class FingerprintsImportPI implements ToCollection, WithBatchInserts, WithChunkReading
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $branch = Branch::whereName('Pulau indah')->first();
        foreach ($rows as $row) {
            $matches = [];
            if (preg_match('/(\d+)\s+(\d+)/', trim($row[0]), $matches) && $matches[1] === $matches[2]) {
                continue;
            }

            try {
                $clocking = Carbon::createFromFormat('d-m-Y h:i:s A', trim($row[4]));
                $date = $clocking->format('Y-m-d');
            } catch (\Exception $e) {
            }

            $fingerprint = Fingerprint::firstOrCreate([
                'staff_id'   => trim($row[0]),
                'staff_name' => trim($row[1]),
                'clocking'   => $clocking, //$clocking->format('Y-m-d H:i:s'),
                'terminal'   => trim($row[9]),
                'branch_id'  => $branch->id,
            ]);

            if ($fingerprint->thumb && $fingerprint->thumb->employee) {
                Attendance::firstOrCreate([
                    'employee_id'     => $fingerprint->thumb->employee->id,
                    'attendance_date' => $date,
                ], [
                    'dept_movement' => 'P',
                    'hr_review'     => 1,
                ]);
            }
        }
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
