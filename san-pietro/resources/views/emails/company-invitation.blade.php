<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invito Cooperativa San Pietro</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #1e3a8a; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background-color: #f9f9f9; }
        .button {
            display: inline-block;
            background-color: #1e3a8a;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer { padding: 20px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cooperativa San Pietro</h1>
            <p>Invito a partecipare alla nostra rete cooperativa</p>
        </div>

        <div class="content">
            <h2>Salve!</h2>

            <p>Siete stati invitati dalla <strong>Cooperativa San Pietro</strong> a unirvi alla nostra rete di cooperative del settore ittico.</p>

            <p><strong>Dettagli dell'azienda invitata:</strong></p>
            <ul>
                <li><strong>Nome Azienda:</strong> {{ $invitation->company_name }}</li>
                @if($invitation->business_type)
                <li><strong>Tipo di Business:</strong> {{ $invitation->business_type }}</li>
                @endif
                @if($invitation->sector)
                <li><strong>Settore:</strong> {{ $invitation->sector }}</li>
                @endif
                <li><strong>Email:</strong> {{ $invitation->email }}</li>
            </ul>

            @if($invitation->permissions && count($invitation->permissions) > 0)
            <p><strong>Funzionalità disponibili:</strong></p>
            <ul>
                @foreach($invitation->permissions as $permission)
                    <li>{{ ucfirst($permission) }}</li>
                @endforeach
            </ul>
            @endif

            <p>Per accettare l'invito e completare la registrazione, cliccate sul pulsante qui sotto:</p>

            <div style="text-align: center;">
                <a href="{{ route('invitations.accept', $invitation->token) }}" class="button">
                    Accetta Invito
                </a>
            </div>

            <p><strong>Importante:</strong></p>
            <ul>
                <li>Questo invito scade il {{ $invitation->expires_at->format('d/m/Y H:i') }}</li>
                <li>Una volta accettato, riceverete le credenziali di accesso per la piattaforma</li>
                <li>Avrete accesso ai vostri dati in modo completamente isolato dalle altre aziende</li>
            </ul>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Cooperativa San Pietro - Sistema di Gestione Cooperativa</p>
            <p>Email: info@sanpietro.it | Tel: 0533123456</p>
            <p>Se non riconoscete questo invito, potete ignorare questa email.</p>
        </div>
    </div>
</body>
</html>