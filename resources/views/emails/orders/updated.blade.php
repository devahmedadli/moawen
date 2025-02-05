<!DOCTYPE html>
<html>

<head>
    <title>Order Status Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello {{ $order->buyer->full_name }},</h2>
        <p>Your order #ORD_{{ $order->id }} status has been updated.</p>
        <ul>
            <li><strong>Service:</strong> {{ $order->service->name }}</li>
            <li><strong>New Status:</strong> {{ ucfirst($order->status) }}</li>
        </ul>
        <a href="{{ route('orders.show', $order->id) }}" class="btn">View Order</a>
        <p>Thank you!</p>
        <p>Regards, Moawen Team</p>
    </div>
</body>

</html>
