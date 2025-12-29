<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket {{ $ticket->order->reference_no }}</title>
    <style>
        body { font-family: sans-serif; }
        .ticket-box { border: 2px dashed #333; padding: 20px; margin-bottom: 20px; page-break-inside: avoid; }
        .header { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .event-name { font-size: 24px; font-weight: bold; color: #333; }
        .meta { color: #666; font-size: 14px; margin-top: 5px; }
        .qr-code { text-align: center; margin-top: 20px; }
        .footer { font-size: 10px; color: #999; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
        .row { display: table; width: 100%; table-layout: fixed; }
        .col { display: table-cell; vertical-align: top; }
        .label { font-weight: bold; color: #555; }
        .value { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <div class="header">
            <div class="event-name">{{ $ticket->ticketType->event->name }}</div>
            <div class="meta">{{ $ticket->ticketType->event->start_date->format('l, d F Y H:i') }}</div>
            <div class="meta">{{ $ticket->ticketType->event->venue->name }} - {{ $ticket->ticketType->event->venue->address }}</div>
        </div>

        <div class="row">
            <div class="col">
                <div class="label">Ticket Type</div>
                <div class="value">{{ $ticket->ticketType->name }}</div>

                <div class="label">Attendee</div>
                <div class="value">{{ $ticket->order->customer_name }}</div>

                <div class="label">Order Ref</div>
                <div class="value">{{ $ticket->order->reference_no }}</div>
            </div>
            <div class="col" style="text-align: center;">
                <div class="qr-code">
                    <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(150)->generate($qrContent)) }} ">
                </div>
                <div style="font-size: 10px; margin-top: 5px;">{{ $ticket->id }}</div>
            </div>
        </div>

        <div class="footer">
            Ticket generated on {{ now()->format('d/m/Y H:i') }}. Please present this ticket at the entrance.
            <br>
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
