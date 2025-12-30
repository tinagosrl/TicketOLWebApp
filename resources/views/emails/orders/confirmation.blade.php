<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .order-details { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .order-details th, .order-details td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; }
        .button { display: inline-block; padding: 10px 20px; background-color: #4F46E5; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Thank you for your order!</h2>
            <p>Order Reference: <strong>{{ $order->reference_no }}</strong></p>
        </div>

        <p>Hi {{ $order->customer_name }},</p>
        <p>Your order has been confirmed. Here is what you purchased:</p>

        <table class="order-details">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->ticketType->name }} ({{ $item->ticketType->event->name }})</td>
                    <td>{{ $item->quantity }}</td>
                    <td>€ {{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                    <td style="font-weight: bold;">€ {{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="text-align: center; margin: 30px 0;">
            <p>Your tickets are ready!</p>
            <a href="{{ $downloadUrl }}" class="button">Download Tickets (PDF)</a>
        </div>

        <p>If the button doesn't work, copy and paste this link into your browser:<br>
        <a href="{{ $downloadUrl }}">{{ $downloadUrl }}</a></p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
