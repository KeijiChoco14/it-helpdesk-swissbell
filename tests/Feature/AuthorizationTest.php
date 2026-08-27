<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $employee = User::where('role', 'employee')->first();
        $response = $this->actingAs($employee)->get('/admin'); // assuming /admin will be the route later

        // For now we don't have the route, so this will be 404, but let's test policy directly or just the RoleMiddleware.
        // We will define a dummy route for testing middleware if needed, but we can also test the policy directly.
        $this->assertTrue($employee->cannot('viewAny', User::class));
        $this->assertTrue($employee->cannot('viewAny', Department::class));
    }

    public function test_it_support_cannot_manage_users(): void
    {
        $support = User::where('role', 'it_support')->first();
        $department = Department::first();
        $this->assertTrue($support->cannot('create', User::class));
        $this->assertTrue($support->cannot('update', $department));
    }

    public function test_it_admin_can_manage_users(): void
    {
        $admin = User::where('role', 'it_admin')->first();
        $department = Department::first();
        $this->assertTrue($admin->can('create', User::class));
        $this->assertTrue($admin->can('update', $department));
    }

    public function test_ticket_policy_for_employee(): void
    {
        $employee = User::where('role', 'employee')->first();
        $otherEmployee = User::where('role', 'employee')->where('id', '!=', $employee->id)->first();

        $ownTicket = Ticket::factory()->create(['user_id' => $employee->id]);
        $otherTicket = Ticket::factory()->create(['user_id' => $otherEmployee->id]);

        $this->assertTrue($employee->can('view', $ownTicket));
        $this->assertTrue($employee->cannot('view', $otherTicket));
        $this->assertTrue($employee->cannot('assign', $ownTicket));
    }
}
