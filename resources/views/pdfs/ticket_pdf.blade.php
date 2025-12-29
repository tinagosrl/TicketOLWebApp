<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Entry Ticket</title>
    <style>
        body { font-family: sans-serif; }
        .ticket-wrapper { page-break-after: always; }
        .ticket-wrapper:last-child { page-break-after: avoid; }
        .ticket-box { border: 2px dashed #333; padding: 20px; page-break-inside: avoid; }
        .header { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .main-title { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #555; margin-bottom: 5px; }
        .event-name { font-size: 24px; font-weight: bold; color: #333; margin-bottom: 5px; }
        .meta { color: #666; font-size: 14px; margin-top: 5px; }
        .qr-code { text-align: center; margin-top: 20px; }
        .footer { font-size: 10px; color: #999; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
        .row { display: table; width: 100%; table-layout: fixed; }
        .col { display: table-cell; vertical-align: top; }
        .label { font-weight: bold; color: #555; font-size: 12px; margin-top: 10px; }
        .value { margin-bottom: 5px; font-size: 14px; }
    </style>
</head>
<body>
    @foreach($tickets as $ticket)
        <div class="ticket-wrapper">
            <div class="ticket-box">
                <div class="header">
                    <div class="main-title">Entry Ticket / Biglietto d'Ingresso</div>
                    <div class="event-name">{{ $ticket->ticketType->event->name }}</div>
                    <div class="meta">{{ $ticket->ticketType->event->venue->name }} - {{ $ticket->ticketType->event->venue->address }}</div>
                    <div class="meta">Validity / Validità: 365 Days / Giorni</div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="label">Ticket Type / Tipo Biglietto</div>
                        <div class="value">{{ $ticket->ticketType->name }}</div>

                        <div class="label">Date / Data</div>
                        <div class="value">{{ $ticket->ticketType->event->start_date->format('d/m/Y H:i') }}</div>

                        <div class="label">Attendee / Partecipante</div>
                        <div class="value">{{ $ticket->order->customer_name }}</div>

                        <div class="label">Order Ref / Rif. Ordine</div>
                        <div class="value">{{ $ticket->order->reference_no }}</div>
                    </div>
                    <div class="col" style="text-align: center;">
                        <div class="qr-code">
                            @php
                                $qrContent = json_encode([
                                    'id' => $ticket->id,
                                    'ref' => $ticket->order->reference_no,
                                    'ts' => $ticket->created_at->timestamp
                                ]);
                            @endphp
                            <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(150)->generate($qrContent)) }} ">
                        </div>
                        <div style="font-size: 10px; margin-top: 5px;">{{ $ticket->unique_code ?? $ticket->id }}</div>
                    </div>
                </div>

                <div class="footer">
                    Generated on / Generato il: {{ now()->format('d/m/Y H:i') }}
                    <br>
                    Please present this ticket at the entrance / Si prega di presentare questo biglietto all'ingresso
                    <br><br>
                    <div style="width: 100%; text-align: center;">
                        <img src="{{ public_path('images/ticketol_logo.png') }}" style="height: 30px; vertical-align: middle; margin-right: 10px; display: inline-block;">
                        <span style="vertical-align: middle; font-size: 11px; color: #555; display: inline-block;">Powered by <strong>TicketOL</strong> - <a href="https://www.ticketol.eu" style="color: #555; text-decoration: none;">www.ticketol.eu</a></span>
                     </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
