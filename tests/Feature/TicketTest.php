<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_employee_can_view_own_tickets_list(): void
    {
        $employee = User::where('role', 'employee')->first();
        Ticket::factory()->create(['user_id' => $employee->id, 'title' => 'My Secret Ticket']);

        $response = $this->actingAs($employee)->get('/tickets');

        $response->assertStatus(200);
        $response->assertSee('My Secret Ticket');
    }

    public function test_employee_cannot_view_others_tickets_list(): void
    {
        $employee1 = User::where('role', 'employee')->first();
        $employee2 = User::where('role', 'employee')->where('id', '!=', $employee1->id)->first();

        Ticket::factory()->create(['user_id' => $employee2->id, 'title' => 'Not My Ticket']);

        $response = $this->actingAs($employee1)->get('/tickets');

        $response->assertStatus(200);
        $response->assertDontSee('Not My Ticket');
    }

    public function test_employee_can_create_ticket(): void
    {
        $employee = User::where('role', 'employee')->first();
        $category = Category::first();

        $response = $this->actingAs($employee)->post('/tickets', [
            'title' => 'Printer is burning',
            'description' => 'Help it is on fire',
            'category_id' => $category->id,
            'priority' => 'Critical',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'title' => 'Printer is burning',
            'user_id' => $employee->id,
        ]);
    }
}
