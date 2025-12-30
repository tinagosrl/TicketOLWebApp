<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Tenant;
use App\Models\TicketType;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $event;
    protected $ticketType;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant with domain
        $this->tenant = Tenant::factory()->create([
            'domain' => 'shop.ticketol.test',
            'is_active' => true
        ]);
        
        $venue = Venue::factory()->create(['tenant_id' => $this->tenant->id]);
        $this->event = Event::factory()->create([
            'tenant_id' => $this->tenant->id,
            'venue_id' => $venue->id,
            'slug' => 'awesome-concert'
        ]);
        $this->ticketType = TicketType::factory()->create([
            'event_id' => $this->event->id,
            'price' => 10.00,
            'quantity' => 100
        ]);
    }

    public function test_can_view_shop_index()
    {
        // Mock subdomain handling if necessary, usually standard requests work if configured
        // Laravel testing helper for host/domain?
        // We can simulate request headers or custom URL
        
        $response = $this->get('http://shop.ticketol.test');
        
        $response->assertStatus(200);
        $response->assertSee($this->event->name);
    }

    public function test_can_view_event_details()
    {
        $response = $this->get('http://shop.ticketol.test/events/awesome-concert');
        
        $response->assertStatus(200);
        $response->assertSee($this->ticketType->name);
    }

    public function test_can_add_to_cart()
    {
        $response = $this->post('http://shop.ticketol.test/cart', [
            'ticket_type_id' => $this->ticketType->id,
            'quantity' => 2
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('cart');
        
        $cart = session('cart');
        $this->assertArrayHasKey($this->ticketType->id, $cart);
        $this->assertEquals(2, $cart[$this->ticketType->id]['quantity']);
    }

    public function test_checkout_flow()
    {
        // Add to cart first
        $this->post('http://shop.ticketol.test/cart', [
            'ticket_type_id' => $this->ticketType->id,
            'quantity' => 1
        ]);

        // Proceed to checkout (GET)
        $response = $this->get('http://shop.ticketol.test/checkout');
        $response->assertStatus(200);

        // Submit order (POST)
        $response = $this->post('http://shop.ticketol.test/checkout', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com'
        ]);

        $response->assertRedirect();
        // Check DB
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'john@example.com',
            'total_amount' => 10.00
        ]);
        
        $this->assertDatabaseHas('tickets', [
            'ticket_type_id' => $this->ticketType->id
        ]);
        
        // Assert TicketType quantity decreased
        $this->assertEquals(99, $this->ticketType->fresh()->quantity);
    }
}
