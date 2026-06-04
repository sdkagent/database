<?php

namespace Database\Factories;

use App\Models\Logging\ApiRequestLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApiRequestLogFactory extends Factory
{
    protected $model = ApiRequestLog::class;

    public function definition(): array
    {
        return [
            'api_client_id' => \App\Models\ApiClient::factory(),
            'order_id'      => null,
            'endpoint'      => fake()->randomElement(['/api/verify', '/api/activate', '/api/license']),
            'method'        => fake()->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'request_data'  => ['key' => fake()->sha256()],
            'response_data' => ['status' => 'success'],
            'status_code'   => fake()->randomElement([200, 201, 400, 401, 403, 500]),
            'ip_address'    => fake()->ipv4(),
        ];
    }

    public function success(): static
    {
        return $this->state(fn(array $attrs) => ['status_code' => 200]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => ['status_code' => 500]);
    }

    public function endpointApiActivate(): static
    {
        return $this->state(['endpoint' => '/api/activate']);
    }

    public function endpointApiLicense(): static
    {
        return $this->state(['endpoint' => '/api/license']);
    }

    public function endpointApiVerify(): static
    {
        return $this->state(['endpoint' => '/api/verify']);
    }

    public function methodDelete(): static
    {
        return $this->state(['method' => 'DELETE']);
    }

    public function methodGet(): static
    {
        return $this->state(['method' => 'GET']);
    }

    public function methodPost(): static
    {
        return $this->state(['method' => 'POST']);
    }

    public function methodPut(): static
    {
        return $this->state(['method' => 'PUT']);
    }
}
