<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;
use App\Models\ContactTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $main_data = [
            'zip_code' => '0186',
            'working_hours' => '09:00 - 18:00',
            'email' => 'info@sps.gov.ge',
            'facebook' => 'https://www.facebook.com/moc.gov.ge',
            'youtube' => 'https://www.youtube.com/user/mclagovofficial',
            'twitter' => 'https://twitter.com/MOC_GOV_GE'
        ];

        $count = Contact::where('id', 1)->exists();

        if(!$count){
            $data = Contact::create($main_data);

            $translate_data = [
                [
                    'parent_id' => $data->id,
                    'address' => 'საქართველო, თბილისი, ზურაბ ანჯაფარიძის ქ.N18 0186',
                    'lang' => 'ka'
                ],
                [
                    'parent_id' => $data->id,
                    'address' => 'Georgia, Tbilisi, Zurab Anjafaridze N18',
                    'lang' => 'en' 
                ]
            ];

            ContactTranslate::insert($translate_data);
        }
    }
}
