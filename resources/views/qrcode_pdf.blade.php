<!DOCTYPE html>
<html>
<head>
    <style>
        .qrcode-container {
            display: flex; /* Align QR codes horizontally */
            flex-wrap: wrap; /* Allows wrapping if necessary */
            gap: 10px; /* Space between QR codes */
        }

        .qrcode {
            display: inline-block;
            margin-right: 10px; /* Space between QR codes */
        }
    </style>
</head>
<body>
    <div class="qrcode-container">
        @foreach ($qrcode as $qrcodes)
            <div class="qrcode">
                {{ $qrcodes }}
            </div>
        @endforeach
    </div>
</body>
</html>
