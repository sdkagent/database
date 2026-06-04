<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LlmTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('llm_providers')->insert([
            [
                'id' => 1,
                'name' => 'OpenAI',
                'api_key' => 'sk-openai-xxxx-xxxx-xxxx',
                'base_url' => 'https://api.openai.com/v1',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Anthropic',
                'api_key' => 'sk-ant-xxxx-xxxx-xxxx',
                'base_url' => 'https://api.anthropic.com/v1',
                'status' => 'active',
            ],
        ]);

        DB::table('llm_provider_settings')->insert([
            [
                'provider_id' => 1,
                'setting_key' => 'model',
                'setting_value' => 'gpt-4o',
            ],
            [
                'provider_id' => 1,
                'setting_key' => 'max_tokens',
                'setting_value' => '4096',
            ],
            [
                'provider_id' => 2,
                'setting_key' => 'model',
                'setting_value' => 'claude-3-opus-20240229',
            ],
            [
                'provider_id' => 2,
                'setting_key' => 'max_tokens',
                'setting_value' => '8192',
            ],
        ]);

        DB::table('llm_provider_activity')->insert([
            [
                'provider_id' => 1,
                'user_id' => 3,
                'activity_type' => 'prompt',
                'details' => '{"tokens": 150, "model": "gpt-4o"}',
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'provider_id' => 1,
                'user_id' => 3,
                'activity_type' => 'response',
                'details' => '{"tokens": 450, "model": "gpt-4o"}',
                'created_at' => '2026-05-30 14:00:05',
            ],
        ]);

        DB::table('llm_provider_usage')->insert([
            [
                'provider_id' => 1,
                'user_id' => 3,
                'tokens_used' => 600,
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'provider_id' => 1,
                'user_id' => 3,
                'tokens_used' => 1200,
                'created_at' => '2026-05-30 15:00:00',
            ],
            [
                'provider_id' => 2,
                'user_id' => 3,
                'tokens_used' => 800,
                'created_at' => '2026-05-30 16:00:00',
            ],
        ]);

        DB::table('llm_provider_logs')->insert([
            [
                'provider_id' => 1,
                'user_id' => 3,
                'prompt' => 'What is the capital of France?',
                'response' => 'The capital of France is Paris.',
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'provider_id' => 1,
                'user_id' => 3,
                'prompt' => 'Explain licensing in simple terms.',
                'response' => 'Licensing is like renting software instead of buying it outright. The license key is your proof of payment.',
                'created_at' => '2026-05-30 14:05:00',
            ],
        ]);
    }
}
