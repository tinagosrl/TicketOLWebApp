<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $event;
    protected $ticketType;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant and User
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        
        // Setup Event infrastructure
        $venue = Venue::factory()->create(['tenant_id' => $this->tenant->id]);
        $this->event = Event::factory()->create([
            'tenant_id' => $this->tenant->id,
            'venue_id' => $venue->id
        ]);
        $this->ticketType = TicketType::factory()->create(['event_id' => $this->event->id]);
    }

    public function test_tenant_can_view_tickets()
    {
        $response = $this->actingAs($this->user)->get(route('tenant.tickets.index'));
        $response->assertStatus(200);
        $response->assertSee('Ticket Management');
    }

    public function test_tenant_can_validate_ticket()
    {
        $order = Order::factory()->create(['tenant_id' => $this->tenant->id]);
        $ticket = Ticket::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id,
            'validated_at' => null
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('tenant.tickets.validate', $ticket->id));

        $response->assertRedirect();
        $this->assertNotNull($ticket->fresh()->validated_at);
        
        // Check Log
        $this->assertDatabaseHas('ticket_logs', [
            'ticket_id' => $ticket->id,
            'action' => 'validated',
            'user_id' => $this->user->id
        ]);
    }

    public function test_tenant_can_unvalidate_ticket()
    {
        $order = Order::factory()->create(['tenant_id' => $this->tenant->id]);
        $ticket = Ticket::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id,
            'validated_at' => now()
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('tenant.tickets.unvalidate', $ticket->id));

        $response->assertRedirect();
        $this->assertNull($ticket->fresh()->validated_at);

        // Check Log
        $this->assertDatabaseHas('ticket_logs', [
            'ticket_id' => $ticket->id,
            'action' => 'unvalidated',
            'user_id' => $this->user->id
        ]);
    }

    public function test_export_functionality()
    {
        // Add some tickets
        $order = Order::factory()->create(['tenant_id' => $this->tenant->id]);
        Ticket::factory()->count(5)->create([
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('tenant.tickets.export', ['format' => 'excel']));

        $response->assertStatus(200);
        // Check for attachment header usually
        $this->assertTrue($response->headers->contains('content-disposition', 'attachment; filename=tickets.xlsx'));
    }

    public function test_filtering_tickets()
    {
        $order = Order::factory()->create(['tenant_id' => $this->tenant->id]);
        $ticket1 = Ticket::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id,
            'unique_code' => 'CODE123'
        ]);
        $ticket2 = Ticket::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id,
            'unique_code' => 'OTHER456'
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('tenant.tickets.index', ['search' => 'CODE123']));

        $response->assertSee('CODE123');
        $response->assertDontSee('OTHER456');
    }

    public function test_pdf_download()
    {
        $order = Order::factory()->create(['tenant_id' => $this->tenant->id]);
        $ticket = Ticket::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id
        ]);
        
        // Signed route needed
        $url = \Illuminate\Support\Facades\URL::signedRoute('orders.download.tickets', ['order' => $order->id]);
        
        $response = $this->get($url);
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
