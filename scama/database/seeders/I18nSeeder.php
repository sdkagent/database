<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class I18nSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('language_packs')->insert([
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'is_rtl' => false,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'es',
                'name' => 'Spanish',
                'native_name' => 'Español',
                'is_rtl' => false,
                'is_default' => false,
                'is_active' => true,
            ],
        ]);

        DB::table('translations')->insert([
            [
                'language_pack_id' => 2,
                'namespace' => 'frontend',
                'group' => 'general',
                'key' => 'welcome_message',
                'value' => '¡Bienvenido a LicensePro!',
            ],
            [
                'language_pack_id' => 2,
                'namespace' => 'frontend',
                'group' => 'general',
                'key' => 'checkout',
                'value' => 'Finalizar Compra',
            ],
        ]);

        DB::table('translation_files')->insert([
            [
                'language_pack_id' => 1,
                'namespace' => 'frontend',
                'file_path' => '/lang/en/messages.php',
                'file_format' => 'json',
                'version' => 1,
            ],
            [
                'language_pack_id' => 2,
                'namespace' => 'frontend',
                'file_path' => '/lang/es/messages.php',
                'file_format' => 'json',
                'version' => 1,
            ],
        ]);
    }
}
