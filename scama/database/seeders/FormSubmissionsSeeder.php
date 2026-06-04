<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormSubmissionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('form_submissions')->insert([
            [
                'id' => 1,
                'form_key' => 'contact',
                'data' => json_encode([
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'message' => 'I have a question about licensing.',
                ]),
                'user_id' => 1,
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0...',
            ],
            [
                'id' => 2,
                'form_key' => 'newsletter',
                'data' => json_encode([
                    'email' => 'jane@example.com',
                    'interests' => ['licensing', 'api'],
                ]),
                'user_id' => null,
                'ip_address' => '203.0.113.1',
                'user_agent' => 'Mozilla/5.0...',
            ],
        ]);
    }
}
