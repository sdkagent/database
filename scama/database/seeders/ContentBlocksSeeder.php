<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentBlocksSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('content_blocks')->insert([
            [
                'key' => 'footer-about',
                'title' => 'Footer About Text',
                'content' => '<p>LicensePro is the leading software licensing platform.</p>',
                'type' => 'html',
                'locations' => json_encode(['footer']),
                'active' => true,
            ],
            [
                'key' => 'home-hero-text',
                'title' => 'Home Hero Text',
                'content' => json_encode([
                    'headline' => 'Simplify Software Licensing',
                    'subtext' => 'Protect, manage, and license your software with ease.',
                ]),
                'type' => 'json',
                'locations' => json_encode(['home']),
                'active' => true,
            ],
            [
                'key' => 'cookie-consent',
                'title' => 'Cookie Consent',
                'content' => '<p>We use cookies to improve your experience.</p>',
                'type' => 'html',
                'locations' => json_encode(['global']),
                'active' => true,
            ],
        ]);
    }
}
