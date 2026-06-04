<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemesCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('themes')->insert([
            [
                'id' => 1,
                'name' => 'Default',
                'slug' => 'default',
                'description' => 'The default theme for the website',
                'version' => '1.1.0',
                'author' => 'LicensePro Team',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Dark Mode',
                'slug' => 'dark-mode',
                'description' => 'A dark-themed alternative skin',
                'version' => '1.0.0',
                'author' => 'LicensePro Team',
                'status' => 'inactive',
            ],
        ]);

        DB::table('theme_settings')->insert([
            [
                'theme_id' => 1,
                'key' => 'primary_color',
                'value' => '#3498db',
            ],
            [
                'theme_id' => 1,
                'key' => 'secondary_color',
                'value' => '#2ecc71',
            ],
            [
                'theme_id' => 2,
                'key' => 'primary_color',
                'value' => '#1a1a2e',
            ],
            [
                'theme_id' => 2,
                'key' => 'secondary_color',
                'value' => '#e94560',
            ],
        ]);

        DB::table('theme_assets')->insert([
            [
                'theme_id' => 1,
                'type' => 'css',
                'path' => '/themes/default/style.css',
            ],
            [
                'theme_id' => 1,
                'type' => 'js',
                'path' => '/themes/default/script.js',
            ],
            [
                'theme_id' => 1,
                'type' => 'image',
                'path' => '/themes/default/logo.png',
            ],
            [
                'theme_id' => 2,
                'type' => 'css',
                'path' => '/themes/dark-mode/style.css',
            ],
        ]);

        DB::table('theme_general_settings')->insert([
            [
                'theme_id' => 1,
                'setting_key' => 'enable_dark_mode',
                'setting_value' => 'true',
            ],
            [
                'theme_id' => 1,
                'setting_key' => 'container_width',
                'setting_value' => '1200px',
            ],
        ]);
    }
}
