<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemesLogsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('theme_customizations')->insert([
            [
                'theme_id' => 1,
                'user_id' => 3,
                'custom_css' => 'body { background-color: #f0f0f0; }',
                'custom_js' => 'console.log("Custom JS Loaded");',
            ],
            [
                'theme_id' => 1,
                'user_id' => 2,
                'custom_css' => 'header { background: linear-gradient(90deg, #3498db, #2ecc71); }',
                'custom_js' => null,
            ],
        ]);

        DB::table('theme_usage_logs')->insert([
            [
                'theme_id' => 1,
                'user_id' => 3,
                'action' => 'activated',
                'ip_address' => '192.168.1.100',
                'created_at' => '2026-05-30 10:00:00',
            ],
            [
                'theme_id' => 1,
                'user_id' => 3,
                'action' => 'customized',
                'ip_address' => '192.168.1.100',
                'created_at' => '2026-05-30 10:15:00',
            ],
            [
                'theme_id' => 2,
                'user_id' => 3,
                'action' => 'activated',
                'ip_address' => '192.168.1.100',
                'created_at' => '2026-05-30 11:00:00',
            ],
        ]);

        DB::table('theme_update_logs')->insert([
            [
                'theme_id' => 1,
                'version_from' => '1.0.0',
                'version_to' => '1.1.0',
                'changelog' => 'Added new color scheme options and improved responsive layout.',
            ],
            [
                'theme_id' => 1,
                'version_from' => '1.1.0',
                'version_to' => '1.2.0',
                'changelog' => 'Fixed mobile navigation bug and updated font assets.',
            ],
        ]);

        DB::table('theme_conflicts')->insert([
            [
                'theme_id' => 1,
                'conflicting_plugin' => 'SEO Optimizer',
                'description' => 'The SEO Optimizer plugin causes layout issues with the Default theme.',
            ],
            [
                'theme_id' => 1,
                'conflicting_plugin' => 'Analytics Pro',
                'description' => 'Analytics Pro conflicts with Default theme\'s JavaScript event handlers.',
            ],
        ]);

        DB::table('theme_generations')->insert([
            [
                'id' => 1,
                'theme_id' => 1,
                'user_id' => 3,
                'theme_name' => 'Custom Blue Theme',
                'theme_description' => 'A custom blue variation of the default theme',
                'status' => 'completed',
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'id' => 2,
                'theme_id' => 2,
                'user_id' => 3,
                'theme_name' => 'Midnight Edition',
                'theme_description' => 'A very dark theme for night-time users',
                'status' => 'completed',
                'created_at' => '2026-05-30 14:30:00',
            ],
        ]);

        DB::table('theme_generation_logs')->insert([
            [
                'generation_id' => 1,
                'message' => 'Theme generation started.',
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'generation_id' => 1,
                'message' => 'Theme generation completed successfully.',
                'created_at' => '2026-05-30 14:02:00',
            ],
            [
                'generation_id' => 2,
                'message' => 'Theme generation started.',
                'created_at' => '2026-05-30 14:30:00',
            ],
            [
                'generation_id' => 2,
                'message' => 'Theme generation completed successfully.',
                'created_at' => '2026-05-30 14:32:00',
            ],
        ]);
    }
}
