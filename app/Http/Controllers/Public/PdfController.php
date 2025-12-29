<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfController extends Controller
{
    public function download(Request $request, Ticket $ticket)
    {
        // Security check: Ensure signed URL
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired URL.');
        }

        $ticket->load(['order', 'ticketType.event.venue']);
        
        // Wrap single ticket in collection for view compatibility
        $tickets = collect([$ticket]);

        $pdf = Pdf::loadView('pdfs.ticket_pdf', compact('tickets'));

        return $pdf->download("ticket-{$ticket->order->reference_no}-{$ticket->id}.pdf");
    }

    public function downloadOrder(Request $request, Order $order)
    {
         // Security check: Ensure signed URL
         if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired URL.');
        }

        $tickets = $order->tickets()->with(['ticketType.event.venue', 'order'])->get();

        $pdf = Pdf::loadView('pdfs.ticket_pdf', compact('tickets'));

        return $pdf->download("order-{$order->reference_no}-tickets.pdf");
    }
}
