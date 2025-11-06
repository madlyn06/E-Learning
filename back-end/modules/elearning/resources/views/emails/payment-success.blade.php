<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 20px 0;
            border-radius: 4px;
            font-weight: bold;
        }
        .details {
            background-color: white;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Successful!</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Thank you for your payment. Your transaction has been completed successfully, and your {{ $item_type }} is now available.</p>
            
            <div class="details">
                <h3>Payment Details:</h3>
                <p><strong>Reference ID:</strong> {{ $reference_id }}</p>
                <p><strong>Amount:</strong> {{ $amount }}</p>
                <p><strong>Date:</strong> {{ $payment_date }}</p>
                <p><strong>{{ ucfirst($item_type) }}:</strong> {{ $item_name }}</p>
            </div>
            
            <p>You can access your {{ $item_type }} by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $item_url }}" class="button">Access My {{ ucfirst($item_type) }}</a>
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
            
            <p>Thank you for choosing our platform!</p>
            
            <p>Best regards,<br>The E-Learning Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} E-Learning System. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>
</html>
