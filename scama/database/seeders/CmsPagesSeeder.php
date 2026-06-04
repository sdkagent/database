<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsPagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cms_pages')->insert([
            [
                'id' => 1,
                'title' => 'Home',
                'slug' => 'home',
                'content' => '<h1>Welcome to LicensePro</h1><p>Your complete licensing solution.</p>',
                'status' => 'published',
                'meta_title' => 'Home | LicensePro',
                'meta_description' => 'Complete software licensing platform with hardware locking, API integration, and subscription management.',
                'noindex' => false,
                'priority' => 1.00,
                'changefreq' => 'daily',
                'sitemap_include' => true,
                'published_at' => '2026-01-01 10:00:00',
                'scheduled_for' => null,
                'author_id' => 1,
                'created_at' => '2026-01-01 10:00:00',
            ],
            [
                'id' => 2,
                'title' => 'About',
                'slug' => 'about',
                'content' => '<h1>About Us</h1><p>We are a leading provider of software licensing solutions.</p>',
                'status' => 'published',
                'meta_title' => 'About | LicensePro',
                'meta_description' => 'Learn about the team behind LicensePro and our mission to simplify software licensing.',
                'noindex' => false,
                'priority' => 0.70,
                'changefreq' => 'monthly',
                'sitemap_include' => true,
                'published_at' => '2026-01-01 10:00:00',
                'scheduled_for' => null,
                'author_id' => 1,
                'created_at' => '2026-01-01 10:00:00',
            ],
            [
                'id' => 3,
                'title' => 'Contact',
                'slug' => 'contact',
                'content' => '<h1>Contact</h1><p>Email: support@licensepro.com</p>',
                'status' => 'published',
                'meta_title' => 'Contact Us | LicensePro',
                'meta_description' => 'Get in touch with the LicensePro team for support, sales, and partnership inquiries.',
                'noindex' => false,
                'priority' => 0.60,
                'changefreq' => 'monthly',
                'sitemap_include' => true,
                'published_at' => '2026-01-01 10:00:00',
                'scheduled_for' => null,
                'author_id' => 1,
                'created_at' => '2026-01-01 10:00:00',
            ],
        ]);

        DB::table('cms_menus')->insert([
            [
                'id' => 1,
                'name' => 'Main Navigation',
                'slug' => 'main-nav',
            ],
            [
                'id' => 2,
                'name' => 'Footer Links',
                'slug' => 'footer-links',
            ],
        ]);

        DB::table('cms_menu_items')->insert([
            [
                'menu_id' => 1,
                'title' => 'Home',
                'url' => '/',
                'target' => '_self',
                'position' => 1,
            ],
            [
                'menu_id' => 1,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'position' => 2,
            ],
            [
                'menu_id' => 1,
                'title' => 'Contact',
                'url' => '/contact',
                'target' => '_self',
                'position' => 3,
            ],
            [
                'menu_id' => 2,
                'title' => 'Privacy Policy',
                'url' => '/privacy',
                'target' => '_blank',
                'position' => 1,
            ],
            [
                'menu_id' => 2,
                'title' => 'Terms of Service',
                'url' => '/terms',
                'target' => '_blank',
                'position' => 2,
            ],
        ]);

        DB::table('cms_widgets')->insert([
            [
                'name' => 'Recent Posts',
                'slug' => 'recent-posts',
                'description' => 'Displays the most recent CMS posts',
            ],
            [
                'name' => 'Newsletter Signup',
                'slug' => 'newsletter',
                'description' => 'Email subscription form widget',
            ],
        ]);

        DB::table('cms_banners')->insert([
            [
                'title' => 'Spring Sale — 30% Off',
                'image' => '/images/banners/spring-sale.jpg',
                'link' => '/pricing',
                'status' => 'published',
                'position' => 1,
            ],
            [
                'title' => 'New Feature: Desktop App',
                'image' => '/images/banners/desktop-app.jpg',
                'link' => '/features',
                'status' => 'published',
                'position' => 2,
            ],
        ]);

        DB::table('cms_testimonials')->insert([
            [
                'name' => 'Alice Johnson',
                'position' => 'CTO',
                'company' => 'TechCorp Inc.',
                'content' => 'LicensePro transformed how we manage software licensing. Highly recommended!',
                'status' => 'published',
            ],
            [
                'name' => 'Bob Williams',
                'position' => 'CEO',
                'company' => 'StartupXYZ',
                'content' => 'The hardware locking feature saved us from piracy. Excellent product.',
                'status' => 'published',
            ],
        ]);
    }
}
