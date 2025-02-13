<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Setting::find(1);

        if(is_null($setting))
        {
            Setting::create([
                'website_title'         => 'Quick',
                'website_logo_dark'     => '',
                'website_logo_light'    => '',
                'website_logo_small'    => '',
                'website_favicon'       => '',
                'meta_title'            => '',
                'meta_description'      => '',
                'meta_tag'              => '',
                'currency_id'           => 1,
                'address'               => 'India, Chandigarh',
                'phone'                 => '+917988684794',
                'email'                 => 'KAMAL KISHORE',
                'facebook'              => '',
                'twitter'               => '',
                'linkedin'              => '',
                'instagram'             => '',
                'github'                => '',
            ]);
        }
    }
}
