<?php

use Illuminate\Database\Seeder;

class RanksTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('ranks')->delete();

        \DB::table('ranks')->insert([
            0 => ['name' => 'DECK DEPARTMENT', 'department' => 0],
            1 => ['name' => 'ENGINE DEPARTMENT', 'department' => 0],
            2 => ['name' => 'ELECTRICAL DEPARTMENT', 'department' => 0],
            3 => ['name' => 'CATERING DEPARTMENT', 'department' => 0],

            4  => ['name' => 'Master', 'department' => 1],
            5  => ['name' => 'Chief Officer', 'department' => 1],
            6  => ['name' => 'Junior Officer', 'department' => 1],
            7  => ['name' => 'A.Ch Officer', 'department' => 1],
            8  => ['name' => '2nd Officer', 'department' => 1],
            9  => ['name' => '3rd Officer', 'department' => 1],
            10 => ['name' => 'A.2nd Officer', 'department' => 1],
            11 => ['name' => 'Able Bodied', 'department' => 1],
            12 => ['name' => 'Bosun', 'department' => 1],
            13 => ['name' => 'Pipe Operator', 'department' => 1],
            14 => ['name' => 'Dredge Master', 'department' => 1],
            15 => ['name' => 'Welder', 'department' => 1],
            16 => ['name' => 'Ordinary Seaman', 'department' => 1],
            17 => ['name' => 'Deck Cadet', 'department' => 1],

            18 => ['name' => 'Chief Engineer', 'department' => 2],
            19 => ['name' => 'A.Ch Eng', 'department' => 2],
            20 => ['name' => '1st Engineer', 'department' => 2],
            21 => ['name' => '2nd Engineer', 'department' => 2],
            22 => ['name' => '3rd Engineer', 'department' => 2],
            23 => ['name' => '4th Engineer', 'department' => 2],
            24 => ['name' => '5th Engineer', 'department' => 2],
            25 => ['name' => 'Junior Engineer', 'department' => 2],
            26 => ['name' => 'Oiler', 'department' => 2],
            27 => ['name' => 'Junior Rating', 'department' => 2],
            28 => ['name' => 'Tr Elect', 'department' => 2],
            29 => ['name' => 'Elect Cadet', 'department' => 2],
            30 => ['name' => 'Engine Cadet', 'department' => 2],
            31 => ['name' => 'Eng Rating', 'department' => 2],

            32 => ['name' => 'Senior Electrician', 'department' => 3],
            33 => ['name' => 'Electrician', 'department' => 3],
            34 => ['name' => 'Junior Electrician', 'department' => 3],
            35 => ['name' => 'Trainee Electrician', 'department' => 3],

            36 => ['name' => 'Cook', 'department' => 4],
            37 => ['name' => 'Steward', 'department' => 4],
        ]);
    }
}
