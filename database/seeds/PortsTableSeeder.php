<?php

use Illuminate\Database\Seeder;

class PortsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('ports')->delete();

        \DB::table('ports')->insert([
            0 => [
                'name'    => 'Assar Senari',
                'country' => 'Malaysia',
            ],
            1 => [
                'name'    => 'Bakapit',
                'country' => 'Malaysia',
            ],
            2 => [
                'name'    => 'Batu Maung',
                'country' => 'Malaysia',
            ],
            3 => [
                'name'    => 'Bintangor',
                'country' => 'Malaysia',
            ],
            4 => [
                'name'    => 'Bintulu',
                'country' => 'Malaysia',
            ],
            5 => [
                'name'    => 'Dermaga Tanjung Lembung',
                'country' => 'Malaysia',
            ],
            6 => [
                'name'    => 'Dickson',
                'country' => 'Malaysia',
            ],
            7 => [
                'name'    => 'Endau',
                'country' => 'Malaysia',
            ],
            8 => [
                'name'    => 'Johor',
                'country' => 'Malaysia',
            ],
            9 => [
                'name'    => 'Kedah',
                'country' => 'Malaysia',
            ],
            10 => [
                'name'    => 'Kemaman',
                'country' => 'Malaysia',
            ],
            11 => [
                'name'    => 'Kertih',
                'country' => 'Malaysia',
            ],
            12 => [
                'name'    => 'Klang',
                'country' => 'Malaysia',
            ],
            13 => [
                'name'    => 'Kota Bahru',
                'country' => 'Malaysia',
            ],
            14 => [
                'name'    => 'Kota Kinabalu',
                'country' => 'Malaysia',
            ],
            15 => [
                'name'    => 'Kuala Belait',
                'country' => 'Malaysia',
            ],
            16 => [
                'name'    => 'Kuala Kedah',
                'country' => 'Malaysia',
            ],
            17 => [
                'name'    => 'Kuala Perlis Harbour',
                'country' => 'Malaysia',
            ],
            18 => [
                'name'    => 'Kuala Trengganu',
                'country' => 'Malaysia',
            ],
            19 => [
                'name'    => 'Kuantan',
                'country' => 'Malaysia',
            ],
            20 => [
                'name'    => 'Kuantan ',
                'country' => 'Malaysia',
            ],
            21 => [
                'name'    => 'Kuching',
                'country' => 'Malaysia',
            ],
            22 => [
                'name'    => 'Kudat',
                'country' => 'Malaysia',
            ],
            23 => [
                'name'    => 'Kunak',
                'country' => 'Malaysia',
            ],
            24 => [
                'name'    => 'Labuan',
                'country' => 'Malaysia',
            ],
            25 => [
                'name'    => 'Lahad Datu',
                'country' => 'Malaysia',
            ],
            26 => [
                'name'    => 'Langkawi',
                'country' => 'Malaysia',
            ],
            27 => [
                'name'    => 'Lumut',
                'country' => 'Malaysia',
            ],
            28 => [
                'name'    => 'Lutong',
                'country' => 'Malaysia',
            ],
            29 => [
                'name'    => 'Melaka',
                'country' => 'Malaysia',
            ],
            30 => [
                'name'    => 'Mersing',
                'country' => 'Malaysia',
            ],
            31 => [
                'name'    => 'Miri',
                'country' => 'Malaysia',
            ],
            32 => [
                'name'    => 'Muar',
                'country' => 'Malaysia',
            ],
            33 => [
                'name'    => 'Nilai Inland',
                'country' => 'Malaysia',
            ],
            34 => [
                'name'    => 'Northport',
                'country' => 'Malaysia',
            ],
            35 => [
                'name'    => 'Pahang',
                'country' => 'Malaysia',
            ],
            36 => [
                'name'    => 'Pelabuhan Bass',
                'country' => 'Malaysia',
            ],
            37 => [
                'name'    => 'Pelabuhan Sungai Udang',
                'country' => 'Malaysia',
            ],
            38 => [
                'name'    => 'Penang',
                'country' => 'Malaysia',
            ],
            39 => [
                'name'    => 'Perak',
                'country' => 'Malaysia',
            ],
            40 => [
                'name'    => 'Rajang',
                'country' => 'Malaysia',
            ],
            41 => [
                'name'    => 'Sabah',
                'country' => 'Malaysia',
            ],
            42 => [
                'name'    => 'Sandakan',
                'country' => 'Malaysia',
            ],
            43 => [
                'name'    => 'Sapangar Bay',
                'country' => 'Malaysia',
            ],
            44 => [
                'name'    => 'Sarikei',
                'country' => 'Malaysia',
            ],
            45 => [
                'name'    => 'Sedili',
                'country' => 'Malaysia',
            ],
            46 => [
                'name'    => 'Selangor',
                'country' => 'Malaysia',
            ],
            47 => [
                'name'    => 'Semporna',
                'country' => 'Malaysia',
            ],
            48 => [
                'name'    => 'Sibu',
                'country' => 'Malaysia',
            ],
            49 => [
                'name'    => 'Sungai Udang',
                'country' => 'Malaysia',
            ],
            50 => [
                'name'    => 'Tanjung Dawai',
                'country' => 'Malaysia',
            ],
            51 => [
                'name'    => 'Tanjung Manis',
                'country' => 'Malaysia',
            ],
            52 => [
                'name'    => 'Tanjung Pelepas',
                'country' => 'Malaysia',
            ],
            53 => [
                'name'    => 'Tawau',
                'country' => 'Malaysia',
            ],
            54 => [
                'name'    => 'Teluk Anson',
                'country' => 'Malaysia',
            ],
            55 => [
                'name'    => 'Teluk Ewa Jetty',
                'country' => 'Malaysia',
            ],
            56 => [
                'name'    => 'Teluk Intan',
                'country' => 'Malaysia',
            ],
            57 => [
                'name'    => 'Terengganu',
                'country' => 'Malaysia',
            ],
            58 => [
                'name'    => 'Tok Bali',
                'country' => 'Malaysia',
            ],
            59 => [
                'name'    => 'Tumpat',
                'country' => 'Malaysia',
            ],
            60 => [
                'name'    => 'Westports',
                'country' => 'Malaysia',
            ],
            61 => [
                'name'    => 'Zarzad',
                'country' => 'Malaysia',
            ],
            62 => [
                'name'    => 'PASIR GUDANG',
                'country' => 'Malaysia',
            ],
        ]);
    }
}
