<!DOCTYPE html>
<html>

<head>
    <title>New Message Received</title>
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
            background-color: #28a745;
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
        <h2>Hello {{ $message->to_user}},</h2>
        <p>You have received a new message from {{ $message->from_user }}:</p>
        <blockquote>
            <p>{{ $message->body }}</p>
        </blockquote>
        <a href="{{ route('chats.show', $message->chat_id) }}" class="btn">View Message</a>
        <p>Regards, Moawen Team</p>
    </div>
</body>

</html>
