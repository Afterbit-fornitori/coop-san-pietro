<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenziali di Accesso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .credentials-box {
            background-color: white;
            border: 2px solid #4F46E5;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box p {
            margin: 10px 0;
        }
        .credentials-box strong {
            color: #4F46E5;
        }
        .button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #4338CA;
        }
        .warning {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Benvenuto in Cooperativa San Pietro</h1>
    </div>

    <div class="content">
        <h2>Ciao {{ $admin->name }},</h2>

        <p>È stata creata la tua azienda <strong>{{ $company->name }}</strong> sulla piattaforma Cooperativa San Pietro.</p>

        <p>Il tuo account amministratore è stato creato con successo. Ecco le tue credenziali di accesso:</p>

        <div class="credentials-box">
            <p><strong>Email:</strong> {{ $admin->email }}</p>
            <p><strong>Password temporanea:</strong> <code style="background-color: #f3f4f6; padding: 5px 10px; border-radius: 3px; font-size: 16px;">{{ $temporaryPassword }}</code></p>
        </div>

        <div class="warning">
            <strong>⚠️ Importante:</strong> Questa è una password temporanea. Per motivi di sicurezza, ti verrà chiesto di cambiarla al primo accesso alla piattaforma.
        </div>

        <p>Puoi accedere alla piattaforma cliccando sul pulsante qui sotto:</p>

        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="button">Accedi alla Piattaforma</a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p><strong>Dettagli azienda:</strong></p>
            <ul style="list-style: none; padding-left: 0;">
                <li>📧 Email: {{ $company->email ?? 'Non specificata' }}</li>
                <li>📞 Telefono: {{ $company->phone ?? 'Non specificato' }}</li>
                <li>📍 Indirizzo: {{ $company->address ?? 'Non specificato' }}</li>
                @if($company->city)
                <li>🏙️ Città: {{ $company->city }} ({{ $company->province ?? '' }})</li>
                @endif
            </ul>
        </div>

        <p style="margin-top: 30px;">Se hai domande o problemi, contatta il supporto tecnico.</p>

        <p>Cordiali saluti,<br><strong>Il Team di Cooperativa San Pietro</strong></p>
    </div>

    <div class="footer">
        <p>Questa email è stata generata automaticamente, si prega di non rispondere.</p>
        <p>&copy; {{ date('Y') }} Cooperativa San Pietro. Tutti i diritti riservati.</p>
    </div>
</body>
</html>
