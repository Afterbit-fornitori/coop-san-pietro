<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->tipo_documento }} {{ $document->serie }}/{{ $document->numero }}/{{ $document->anno }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .header-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .header-row {
            display: table-row;
        }

        .header-cell {
            display: table-cell;
            padding: 5px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .header-cell strong {
            font-weight: bold;
        }

        .section {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            text-decoration: underline;
        }

        .info-row {
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px;
            font-weight: bold;
            text-align: left;
            font-size: 10px;
        }

        table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            margin-top: 15px;
            float: right;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .total-row.final {
            font-weight: bold;
            font-size: 12px;
            border-bottom: 2px solid #000;
            margin-top: 5px;
        }

        .signatures {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 10px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            text-align: center;
            color: #666;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .items-table {
            width: 90%;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header con tipo documento e numerazione -->
        <div class="header">
            <h1>
                @if($document->tipo_documento === 'DDT')
                DOCUMENTO DI TRASPORTO
                @elseif($document->tipo_documento === 'DTN')
                DOCUMENTO DI TRASPORTO NAZIONALE
                @else
                DOCUMENTO DI TRASPORTO REGIONALE
                @endif
            </h1>

            <div class="header-grid">
                <div class="header-row">
                    <div class="header-cell">
                        <strong>Serie:</strong> {{ $document->serie }}
                    </div>
                    <div class="header-cell">
                        <strong>Numero:</strong> {{ str_pad($document->numero, 4, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="header-cell">
                        <strong>Anno:</strong> {{ $document->anno }}
                    </div>
                    <div class="header-cell">
                        <strong>Data:</strong> {{ \Carbon\Carbon::parse($document->data_documento)->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sezione Mittente (Azienda) -->
        <div class="section">
            <div class="section-title">MITTENTE</div>
            <div class="info-row"><strong>{{ $company->name }}</strong></div>
            @if($company->address)
            <div class="info-row">{{ $company->address }}</div>
            <div class="info-row">{{ $company->zip_code }} {{ $company->city }} ({{ $company->province }})</div>
            @endif
            @if($company->vat_number)
            <div class="info-row">P.IVA: {{ $company->vat_number }}</div>
            @endif
            @if($company->tax_code)
            <div class="info-row">C.F.: {{ $company->tax_code }}</div>
            @endif
            @if($company->phone)
            <div class="info-row">Tel: {{ $company->phone }}</div>
            @endif
        </div>

        <!-- Sezione Destinatario (Cliente) -->
        <div class="section">
            <div class="section-title">DESTINATARIO</div>
            <div class="info-row"><strong>{{ $client->business_name }}</strong></div>
            <div class="info-row">{{ $client->address }}</div>
            <div class="info-row">{{ $client->postal_code }} {{ $client->city }} ({{ $client->province }})</div>
            @if($client->vat_number)
            <div class="info-row">P.IVA: {{ $client->vat_number }}</div>
            @endif
            @if($client->tax_code)
            <div class="info-row">C.F.: {{ $client->tax_code }}</div>
            @endif
        </div>

        <!-- Dettagli Trasporto -->
        <div class="section">
            <div class="section-title">DETTAGLI TRASPORTO</div>
            <div class="info-row"><strong>Causale:</strong> {{ $document->causale_trasporto }}</div>
            @if($document->ora_partenza)
            <div class="info-row"><strong>Ora Partenza:</strong> {{ $document->ora_partenza }}</div>
            @endif
            @if($document->data_raccolta)
            <div class="info-row"><strong>Data Raccolta:</strong> {{ \Carbon\Carbon::parse($document->data_raccolta)->format('d/m/Y') }}</div>
            @endif
            @if($document->mezzo_trasporto)
            <div class="info-row"><strong>Mezzo di Trasporto:</strong> {{ $document->mezzo_trasporto }}</div>
            @endif
            @if($productionZone)
            <div class="info-row"><strong>Zona di Produzione:</strong> {{ $productionZone->codice }} - {{ $productionZone->nome }}</div>
            @endif
            @if($member)
            <div class="info-row"><strong>Produttore:</strong> {{ $member->full_name }}</div>
            @endif
        </div>

        <!-- Tabella Prodotti -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Codice</th>
                    <th style="width: 30%;">Descrizione</th>
                    <th class="text-center" style="width: 12%;">Quantità (Kg)</th>
                    <th class="text-center" style="width: 12%;">N. Colli</th>
                    <th class="text-right" style="width: 15%;">Prezzo Unit. (€)</th>
                    <th class="text-right" style="width: 15%;">Totale (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->product->codice }}</td>
                    <td>
                        {{ $item->product->nome_commerciale }}
                        @if($item->product->nome_scientifico)
                        <br><small><em>{{ $item->product->nome_scientifico }}</em></small>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->quantita_kg, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $item->numero_colli }}</td>
                    <td class="text-right">{{ number_format($item->prezzo_unitario, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->totale, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Annotazioni -->
        @if($document->annotazioni)
        <div class="section">
            <div class="section-title">ANNOTAZIONI</div>
            <div>{{ $document->annotazioni }}</div>
        </div>
        @endif

        <!-- Totali -->
        <div class="clearfix">
            <div class="totals">
                <div class="total-row">
                    <span>Totale Kg:</span>
                    <span><strong>{{ $totals['totale_kg'] }}</strong></span>
                </div>
                <div class="total-row">
                    <span>Totale Colli:</span>
                    <span><strong>{{ $totals['totale_colli'] }}</strong></span>
                </div>
                <div class="total-row">
                    <span>Imponibile:</span>
                    <span>€ {{ $totals['totale_imponibile'] }}</span>
                </div>
                <div class="total-row">
                    <span>IVA (10%):</span>
                    <span>€ {{ $totals['iva'] }}</span>
                </div>
                <div class="total-row final">
                    <span>TOTALE DOCUMENTO:</span>
                    <span>€ {{ $totals['totale_documento'] }}</span>
                </div>
            </div>
        </div>

        <!-- Firme -->
        <div class="signatures">
            <div class="signature-box">
                <div>Firma Mittente</div>
                <div class="signature-line">_______________________</div>
            </div>
            <div class="signature-box">
                <div>Firma Destinatario</div>
                <div class="signature-line">_______________________</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Documento generato il {{ now()->format('d/m/Y H:i') }} - {{ $company->name }}
            @if($company->pec)
            - PEC: {{ $company->pec }}
            @endif
        </div>
    </div>
</body>

</html>