<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_new_ticket_notifies_admins(): void
    {
        $employee = User::where('role', 'employee')->first();
        $admin = User::where('role', 'it_admin')->first();
        $category = Category::first();

        $this->actingAs($employee)->post('/tickets', [
            'title' => 'Internet is down',
            'description' => 'Help',
            'category_id' => $category->id,
            'priority' => 'High',
        ]);

        $this->assertTrue($admin->unreadNotifications->count() > 0);
        $this->assertEquals('New ticket created by '.$employee->name, $admin->unreadNotifications->first()->data['message']);
    }

    public function test_assigning_ticket_notifies_support(): void
    {
        $admin = User::where('role', 'it_admin')->first();
        $support = User::where('role', 'it_support')->first();
        $ticket = Ticket::factory()->create();

        $this->actingAs($admin)->post("/tickets/{$ticket->id}/assign", [
            'assigned_to' => $support->id,
        ]);

        $this->assertTrue($support->unreadNotifications->count() > 0);
    }
}
