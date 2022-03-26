<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->name,
        'email'     => $faker->unique()->safeEmail,
        'password'  => bcrypt('secret'),
        'active'    => true,
        'confirmed' => true,
        'locale'    => app()->getLocale(),
        'timezone'  => config('app.timezone'),
    ];
});

$factory->define(App\Models\Meta::class, function (Faker\Generator $faker) {
    return [
        'title' => [
            'en' => $faker->sentence,
            'fr' => $faker->sentence,
            'es' => $faker->sentence,
            'ar' => $faker->sentence,
        ],
        'description' => [
            'en' => $faker->sentences(3, true),
            'fr' => $faker->sentences(3, true),
            'es' => $faker->sentences(3, true),
            'ar' => $faker->sentences(3, true),
        ],
    ];
});

$factory->define(Spatie\Tags\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => [
            'en' => $faker->unique()->word,
            'fr' => $faker->unique()->word,
            'es' => $faker->unique()->word,
            'ar' => $faker->unique()->word,
        ],
    ];
});

$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {
    $publishedDate = null;
    $unPublishedDate = null;

    if ($faker->boolean) {
        $publishedDate = $faker->dateTimeBetween('-2 days', '+2 days');
        $unPublishedDate = $faker->dateTimeBetween($publishedDate, '+2 days');
    }

    return [
        'title' => [
            'en' => $faker->sentence,
            'fr' => $faker->sentence,
            'es' => $faker->sentence,
            'ar' => $faker->sentence,
        ],
        'summary' => [
            'en' => $faker->sentences(3, true),
            'fr' => $faker->sentences(3, true),
            'es' => $faker->sentences(3, true),
            'ar' => $faker->sentences(3, true),
        ],
        'status'         => $faker->numberBetween(0, 2),
        'promoted'       => $faker->boolean(10),
        'pinned'         => $faker->boolean(5),
        'published_at'   => $publishedDate,
        'unpublished_at' => $unPublishedDate,
    ];
});

$factory->define(App\Models\Seafarer::class, function (Faker\Generator $faker) {
    $gender = $faker->randomElement(['Male', 'Female']);
    $nationality = $faker->randomElement(\Illuminate\Support\Facades\Config::get('constants.nationalities'));

    return [
        'name'           => $faker->firstName($gender),
        'surname'        => $faker->lastName(),
        'sex'            => $gender,
        'religion'       => $faker->randomElement(\Illuminate\Support\Facades\Config::get('constants.religions')),
        'nationality'    => $nationality,
        'address'        => $faker->address(),
        'nric_no'        => ('Malaysian' === $nationality) ? mt_rand(100000, 999999).'-'.mt_rand(10, 99).'-'.mt_rand(1000, 9999) : null,
        'date_of_join'   => $faker->date(),
        'date_of_birth'  => $faker->date(),
        'place_of_birth' => $faker->city(),
    ];
});

$factory->define(App\Models\EmployeeContactInfo::class, function (Faker\Generator $faker) {
    return [
        'personal_hp'      => $faker->phoneNumber(),
        'next_of_kin_name' => $faker->name(),
        'next_of_kin_hp'   => $faker->phoneNumber(),
    ];
});

$factory->define(App\Models\EmployeeMedicalInfo::class, function (Faker\Generator $faker) {
    $testedBy = $faker->randomElement(['Vessel', 'Officer']);

    return [
        'tested_by'             => $testedBy,
        'insurance_company'     => $faker->company(),
        'insurance_type'        => $faker->sentence(),
        'insurance_issue_date'  => $faker->date(),
        'insurance_expiry_date' => $faker->date(),
    ];
});

$factory->define(App\Models\EmployeeFinancialInfo::class, function (Faker\Generator $faker) {
    $payment_method = $faker->randomElement(['Cash', 'Bank', 'Home Allotment']);

    return [
          'payment_method'    => $payment_method,
          'bank'              => ('Bank' === $payment_method || 'Home Allotment' === $payment_method) ? $faker->randomElement(['RHB', 'CIMB', 'Maybank', 'H.LEONG']) : null,
          'account_no'        => ('Bank' === $payment_method || 'Home Allotment' === $payment_method) ? $faker->bankAccountNumber() : null,
          'swift_code'        => ('Home Allotment' === $payment_method) ? $faker->swiftBicNumber() : null,
          'country_of_origin' => ('Home Allotment' === $payment_method) ? $faker->country() : null,
    ];
});

$factory->define(App\Models\EmployeeCapabilitiesInfo::class, function (Faker\Generator $faker) {
    $security_course_options = [
        'DSD',
        'SSA',
        'SSO',
    ];

    $other_courses_options = [
        'BST',
        'TANK FAM',
        'AFF',
        'MFA',
        'SUR CRAFT',
        'F/HANDLE',
    ];

    $coc_certificate_types = [
        'C/E Officer of 3000kW or more UL',
        'Engineering watch of 750kW or NC',
        'C/E Officer of 3000kW or more NC',
        'C/E OFFICER CLASS 1',
        'WKO OF 500GT OR MORE NC',
        'WKE 750KW UL',
        'CHIEF MATE of 3000 GT or MORE',
        'MASTER OF 3000GT OR MORE UL',
        'ASE',
    ];

    $certified = mt_rand(0, 1);

    return [
        'rank'                  => $faker->randomElement(array_merge(\Illuminate\Support\Facades\Config::get('constants.ranks.DECK DEPARTMENT'), \Illuminate\Support\Facades\Config::get('constants.ranks.ENGINE DEPARTMENT'))),
        'other_courses'         => json_encode($faker->randomElements($other_courses_options, 3)),
        'security_course'       => json_encode($faker->randomElements($security_course_options, 2)),
        'coc_certificate_type'  => ($certified) ? $faker->randomElement($coc_certificate_types) : null,
        'coc_certificate_class' => ($certified) ? $faker->randomElement(['NC', 'FG']) : null,
    ];
});

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        'document_type'        => 'PASSPORT',
        'document_no'          => str_random(),
        'document_expiry_date' => $faker->dateTimeBetween('-1 year', '+8 year'),
    ];
}, 'PASSPORT');

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        'document_type'        => 'DISCHARGE_BOOK',
        'document_no'          => str_random(),
        'document_expiry_date' => $faker->dateTimeBetween('-1 year', '+8 year'),
    ];
}, 'DISCHARGE_BOOK');

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        'document_type'        => 'WORK_PERMIT',
        'document_no'          => str_random(),
        'document_expiry_date' => $faker->dateTimeBetween('-1 year', '+8 year'),
    ];
}, 'WORK_PERMIT');

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        'document_type'        => 'MEDICAL_CERT',
        'document_no'          => str_random(),
        'document_expiry_date' => $faker->dateTimeBetween('-1 year', '+8 year'),
    ];
}, 'MEDICAL_CERT');

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        'document_type'        => 'COC_CERT',
        'document_no'          => str_random(),
        'document_expiry_date' => $faker->dateTimeBetween('-1 year', '+8 year'),
    ];
}, 'COC_CERT');

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        'document_type'        => 'SMC',
        'document_no'          => str_random(),
        'document_expiry_date' => $faker->dateTimeBetween('-1 year', '+8 year'),
    ];
}, 'SMC');
