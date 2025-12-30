<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\Tenant\TeamController;
use App\Http\Middleware\CheckTenantActive;
use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\TicketController;

// Public Shop Routes (Subdomain)
Route::domain("{domain}." . parse_url(config("app.url"), PHP_URL_HOST))->middleware(['identify.tenant'])->group(function () {
    Route::get("/", [\App\Http\Controllers\Public\ShopController::class, "index"])->name("public.shop.index");
    Route::get("/cart", [\App\Http\Controllers\Public\CartController::class, "index"])->name("public.cart.index");
    Route::post("/cart", [\App\Http\Controllers\Public\CartController::class, "store"])->name("public.cart.store");
    Route::delete("/cart/{id}", [\App\Http\Controllers\Public\CartController::class, "destroy"])->name("public.cart.destroy");
    Route::get("/events/{slug}", [\App\Http\Controllers\Public\ShopController::class, "show"])->name("public.shop.show");
    Route::get("/checkout", [\App\Http\Controllers\Public\CartController::class, "checkout"])->name("public.shop.checkout.index");
    Route::post("/checkout", [\App\Http\Controllers\Public\CheckoutController::class, "store"])->name("public.shop.checkout.store");
    Route::get("/checkout/success/{reference}", [\App\Http\Controllers\Public\CheckoutController::class, "success"])->name("public.shop.checkout.success");
});

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Public Utilities
Route::get("/language/{locale}", [\App\Http\Controllers\LanguageController::class, "switch"])->name("language.switch");
Route::get('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::post('/invitations/{token}', [InvitationController::class, 'process'])->name('invitations.process');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Super Admin Routes
    Route::middleware([CheckSuperAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('plans', PlanController::class)->only(['index', 'edit', 'update']);
        Route::resource('tenants', \App\Http\Controllers\SuperAdmin\TenantController::class);
        Route::resource("email_templates", \App\Http\Controllers\SuperAdmin\EmailTemplateController::class);
        Route::get("branding", [\App\Http\Controllers\SuperAdmin\BrandingController::class, "edit"])->name("branding.edit");
        Route::patch("branding", [\App\Http\Controllers\SuperAdmin\BrandingController::class, "update"])->name("branding.update");
        // System Config Routes
        Route::get("system-config", [\App\Http\Controllers\SuperAdmin\SystemConfigController::class, "edit"])->name("system_config.edit");
        Route::patch("system-config", [\App\Http\Controllers\SuperAdmin\SystemConfigController::class, "update"])->name("system_config.update");
        // Test Email Route
        Route::post("system-config/test-email", [\App\Http\Controllers\SuperAdmin\SystemConfigController::class, "sendTestEmail"])->name("system_config.test_email");
    });
    
    // Billing Routes (Shared)
    Route::get('/billing/payment', [BillingController::class, 'payment'])->name('billing.payment');
    Route::post('/billing/payment', [BillingController::class, 'process'])->name('billing.process');

    // Tenant Routes (Protected by Subscription/Active Check)
    Route::middleware([CheckTenantActive::class])->group(function () {
        Route::get("/dashboard", [\App\Http\Controllers\Tenant\DashboardController::class, "index"])->name("dashboard");        
        
        // Team Management
        Route::get('/team', [TeamController::class, 'index'])->name('tenant.team.index');
        Route::post('/team', [TeamController::class, 'store'])->name('tenant.team.store');
        Route::delete('/team/{invitation}', [TeamController::class, 'destroy'])->name('tenant.team.destroy');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Ticketing Core
        Route::resource("venues", \App\Http\Controllers\Tenant\VenueController::class)->names("tenant.venues");
        Route::resource("events", \App\Http\Controllers\Tenant\EventController::class)->names("tenant.events");
        Route::resource("ticket_types", \App\Http\Controllers\Tenant\TicketTypeController::class)->only(["store", "destroy"])->names("tenant.ticket_types");
        
        // Ticket Management
        Route::get("/tickets", [TicketController::class, "index"])->name("tenant.tickets.index");
        Route::post("/tickets/{ticket}/validate", [TicketController::class, "validateTicket"])->name("tenant.tickets.validate");
        Route::delete("/tickets/{ticket}", [TicketController::class, "destroy"])->name("tenant.tickets.destroy");
        Route::get("/tickets/export", [TicketController::class, "export"])->name("tenant.tickets.export");
        Route::post("/tickets/{ticket}/unvalidate", [TicketController::class, "unvalidateTicket"])->name("tenant.tickets.unvalidate");
        
        // Shop Settings
        Route::get("/settings/shop", [\App\Http\Controllers\Tenant\ShopSettingController::class, "edit"])->name("tenant.shop.settings.edit");
        Route::patch("/settings/shop", [\App\Http\Controllers\Tenant\ShopSettingController::class, "update"])->name("tenant.shop.settings.update");
    });
});

require __DIR__.'/auth.php';

use App\Http\Controllers\Public\PdfController;

Route::get('/tickets/{ticket}/download', [PdfController::class, 'download'])
    ->name('tickets.download')
    ->middleware('signed');

Route::get('/orders/{order}/download-tickets', [PdfController::class, 'downloadOrder'])
    ->name('orders.download.tickets')
    ->middleware('signed');
