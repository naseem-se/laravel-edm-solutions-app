<!-- resources/views/emails/shift-invitation.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .details {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-item {
            margin: 10px 0;
            font-size: 14px;
        }
        .detail-label {
            color: #666;
            font-weight: bold;
        }
        .detail-value {
            color: #333;
            margin-left: 10px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #764ba2;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .expires-warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Shift Invitation</h1>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $worker->full_name }}</strong>,</p>

            <p>{{ $facility->full_name }} has invited you to fill a shift.</p>

            <div class="details">
                <div class="detail-item">
                    <span class="detail-label">Shift Title:</span>
                    <span class="detail-value">{{ $shift->title }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $shift->date }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ $shift->start_time }} - {{ $shift->end_time }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Facility:</span>
                    <span class="detail-value">{{ $facility->name }}</span>
                </div>
            </div>

            <div class="expires-warning">
                ⏰ This invitation will expire in 3 days. Please respond as soon as possible.
            </div>

            <div class="button-container">
                <a href="{{ $webLink }}" class="button">View & Respond to Invitation</a>
            </div>

            <p style="color: #666; font-size: 14px;">
                Or copy and paste this link in your browser:<br>
                <a href="{{ $webLink }}" style="color: #667eea; word-break: break-all;">{{ $webLink }}</a>
            </p>
        </div>

        <div class="footer">
            <p>{{ config('app.name') }}</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>