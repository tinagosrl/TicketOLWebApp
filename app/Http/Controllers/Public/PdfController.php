<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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

        // QR Content: Ticket ID + Secret (In real app, add a hash or UUID)
        $qrContent = json_encode([
            'id' => $ticket->id,
            'ref' => $ticket->order->reference_no,
            'ts' => now()->timestamp
        ]);

        $pdf = Pdf::loadView('pdfs.ticket_pdf', compact('ticket', 'qrContent'));

        return $pdf->download("ticket-{$ticket->order->reference_no}-{$ticket->id}.pdf");
    }
}
