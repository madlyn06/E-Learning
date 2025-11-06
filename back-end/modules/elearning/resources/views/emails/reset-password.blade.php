<!DOCTYPE html>
<html>

<head>
    <title>Verify your account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
            color: #333333;
            padding: 20px;
        }

        .container {
            max-width: 480px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            color: #4a90e2;
            font-weight: 600;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        a.verify-button {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #4a90e2;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 600;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        a.verify-button:hover {
            background-color: #357ABD;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Reset password</h1>

        <p>Hi {{ $name }},</p>
        <p>We received a request to reset your password. Please click the button below to reset your password:</p>
        <a href="{{ $resetLink }}" class="verify-button">Reset Password</a>
        <p class="footer">If you did not request this, please ignore this email.</p>
    </div>
</body>

</html>