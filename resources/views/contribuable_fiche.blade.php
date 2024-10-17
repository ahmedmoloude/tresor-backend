<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Contribuable</title>
    <style>
        body { 
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .page {
            width: 100%;
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
        }
        .header-img {
            width: 100%;
            max-height: 150px; /* Adjust this value as needed */
            object-fit: contain;
            margin-bottom: 20px;
        }
        .header {
            background-image:  {{public_path('header.png')}};
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            position: relative;
        }
        .logo {
            width: 384px;
            margin-left: auto;
            margin-right: auto;
        }
        .divider {
            position: relative;
            margin: 8px 0;
            height: 10px;
        }
        .divider hr {
            position: absolute;
            width: 100%;
            margin: 0 -24px;
            border: none;
            height: 1.5px;
        }
        .divider hr:nth-child(1) { top: 0; background-color: #006400; }
        .divider hr:nth-child(2) { top: 3px; background-color: #FFD700; }
        .divider hr:nth-child(3) { top: 6px; background-color: #FF0000; }
        .rim-logo {
            position: absolute;
            right: 48px;
            top: -48px;
            width: 96px;
            height: 96px;
        }
        .header-text {
            width: 100%;
        }
        .header-text td {
            vertical-align: top;
            padding: 0 16px;
        }
        .left-align {
            text-align: left;
        }
        .right-align {
            text-align: right;
        }
        .center-align {
            text-align: center;
        }
        .main-content {
        }
        .footer {
            padding: 8px 24px;
            position: absolute;
            bottom: 0;
            width: calc(100% - 48px);
        }
        .footer hr {
            border: none;
            height: 1px;
            background-color: #000;
            margin: 8px 0;
        }
        .footer-content {
            width: 100%;
        }
        .footer-content td {
            vertical-align: top;
        }
        .qr-code {
            width: 48px;
            height: auto;
        }
        .specfique_table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .specfique_table th, .specfique_table td {
            border: 1px solid black;
            padding: 5px;
        }
        .specfique_table th {
            background-color: #add8e6;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="page">
        <img class="header-img" src="{{ public_path('header.png') }}" alt="">
        <div class="main-content">
            <h3 class="center-align">Fiche contribuable : {{ $contribuable->libelle }}</h3>
            <h3 class="center-align">Année {{ $annee }}</h3>

            <table class="specfique_table">
                <tr>
                    <th colspan="2">Info</th>
                </tr>
                <tr>
                    <td colspan="2"><b>Nom:</b> {{ $contribuable->libelle }}</td>
                </tr>
                <tr>
                    <td><b>Adresse:</b> {{ $contribuable->adresse }}</td>
                    <td><b>Téléphone:</b> {{ $contribuable->telephone }}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Répresentant:</b> {{ $contribuable->representant }}</td>
                </tr>
                <tr>
                    <td><b>Activité:</b> {{ $contribuable->activite->libelle }}</td>
                    <td><b>Montant:</b> {{ number_format((float)($contribuable->montant), 2) }}</td>
                </tr>
                <tr>
                    <td><b>Emplacement:</b> {{ $contribuable->ref_emplacement_activite->libelle }}</td>
                    <td><b>Taille:</b> {{ $contribuable->ref_taille_activite->libelle }}</td>
                </tr>
            </table>

            <h3>Protocoles des payements</h3>
            <table class="specfique_table">
                <tr>
                    <th>Protocole</th>
                    <th>Description</th>
                    <th>Montant</th>
                    <th>Paiements</th>
                </tr>
                @foreach ($protocoles as $protocole)
                    <tr>
                        <td colspan="4">{{ $protocole->libelle }} (Date: {{ $protocole->dateEch }})</td>
                    </tr>
                    @php
                        $montants = 0;
                        $payements = App\Models\Payement::where('contribuable_id', $contribuable->id)
                            ->where('protocol_id', $protocole->id)
                            ->where('annee', $annee)
                            ->get();
                    @endphp
                    @foreach ($payements as $p)
                        <tr>
                            <td colspan="2">{{ $p->libelle }} (Date: {{ $p->created_at }})</td>
                            <td class="text-right">{{ number_format((float)($p->montant), 2) }}</td>
                            <td></td>
                        </tr>
                        @php $montants += $p->montant; @endphp
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="3"><b>Total des payements</b></td>
                    <td class="text-right">{{ number_format((float)($montantsgen), 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Total reste</b></td>
                    <td class="text-right">{{ number_format((float)($contribuable->montant - $montantsgen), 2) }}</td>
                </tr>
            </table>

            <p class="right-align"><b>Le Directeur</b></p>
        </div>

        <div class="footer">
            <hr>
            <table class="footer-content">
                <tr>
                    <td>
                        <!-- <p><img src="{{ public_path('phone-icon.jpeg') }}" alt="Phone" style="width: 16px; vertical-align: middle;"> 00222 45 74 90 08</p>
                        <p><img src="{{ public_path('email-icon.jpeg') }}" alt="Email" style="width: 16px; vertical-align: middle;"> contact@tresor.mr</p>
                        <p><img src="{{ public_path('web-icon.jpeg') }}" alt="Website" style="width: 16px; vertical-align: middle;"> tresor.mr</p> -->
                    </td>
                    <td class="right-align">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>