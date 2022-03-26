<?php

use App\Models\Seafarer;
use Illuminate\Database\Seeder;

class SeafarerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws \InvalidArgumentException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        // 200 random posts
        /** @var \Illuminate\Database\Eloquent\Collection $posts */
        $seafarers = factory(Seafarer::class)->times(300)->create();

        $seafarers->each(function (Seafarer $seafarer) use ($faker) {
            $seafarer->contactInfo()->save(factory(\App\Models\EmployeeContactInfo::class)->make());

            $seafarer->financialInfo()->save(factory(\App\Models\EmployeeFinancialInfo::class)->make());

            $seafarer->medicalInfo()->save(factory(\App\Models\EmployeeMedicalInfo::class)->make());
            $seafarer->capabilitiesInfo()->save(factory(\App\Models\EmployeeCapabilitiesInfo::class)->make());

            $seafarer->documents()->save(factory(\App\Models\Document::class, 'PASSPORT')->make());
            $seafarer->documents()->save(factory(\App\Models\Document::class, 'SMC')->make());
            $seafarer->documents()->save(factory(\App\Models\Document::class, 'DISCHARGE_BOOK')->make());
            $seafarer->documents()->save(factory(\App\Models\Document::class, 'WORK_PERMIT')->make());
            $seafarer->documents()->save(factory(\App\Models\Document::class, 'MEDICAL_CERT')->make());
            $seafarer->documents()->save(factory(\App\Models\Document::class, 'COC_CERT')->make());
        });
    }
}
