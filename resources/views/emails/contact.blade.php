<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correo Corporativo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }
        .header {
            background-color: #0E7490;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eaeaea;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Solicitud de contacto</h1>
        </div>
        <div class="content">
            <p>Estimado/a usuario, una persona ha intentado contactar con nosotros mediante el sitio corporativo,</p>
            <p>Hemos recibido los siguientes detalles:</p>
            <p><strong>Nombre:</strong> {{$data['name']}}</p>
            <p><strong>Correo Electrónico:</strong> {{$data['email']}}</p>
            <p><strong>Mensaje:</strong> {{$data['message']}}</p>
            <p>Gracias por contactarnos. Nos pondremos en contacto contigo a la brevedad.</p>
        </div>
        <div class="footer">
            <p>Saludos cordiales,<br>El equipo de Apexally</p>
        </div>
    </div>
</body>
</html>
