<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSetupTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * A basic feature test example.
     */
    public function test_database_is_seeded_correctly(): void
    {
        $this->assertDatabaseCount('departments', 8);
        $this->assertDatabaseCount('categories', 5);
        $this->assertDatabaseCount('users', 8);
        $this->assertDatabaseCount('tickets', 20);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@hotel.com',
            'role' => 'it_admin',
        ]);
    }
}
