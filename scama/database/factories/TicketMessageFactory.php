<?php

namespace Database\Factories;

use App\Models\Support\TicketMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketMessageFactory extends Factory
{
    protected $model = TicketMessage::class;

    public function definition(): array
    {
        return [
            'ticket_id'   => \App\Models\Ticket::factory(),
            'sender_id'   => \App\Models\User::factory(),
            'message'     => fake()->paragraph(),
            'attachments' => [],
        ];
    }
}
