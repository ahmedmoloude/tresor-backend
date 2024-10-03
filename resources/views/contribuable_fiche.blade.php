<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Contribuable</title>
    <style>
        body { 
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            width: 100%;
        }
        /* .header td {
            vertical-align: top;
            width: 20.33%;
        } */

        .header table  th, td { 
            border: 0px; 
            padding: 5px; 
        }
        .logo {
            width: 100px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .title {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        th { 
            border: 1px solid black; 
            padding: 5px; 
        }


        .specfique_table td {
            border: 1px solid black;
            padding: 5px;
        }
        th {
            background-color: #add8e6;
        }
        .text-right { 
            text-align: right; 
        }
        .arabic {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
    <table class="header" border="0">
        <tr>
            <td>
                <p>République Islamique de Mauritanie</p>
                <p>Honneur - Fraternité - Justice</p>
                <p>Ministère des Finances</p>
                <p>Direction Régionale du Trésor à Nouadhibou</p>
            </td>
            <td style="text-align: center;">
                <img src="{{ public_path('logo.png') }}" alt="Logo" class="logo">
            </td>
            <td class="arabic">
                <p>الجمهورية الإسلامية الموريتانية</p>
                <p>شرف - إخاء - عدل</p>
                <p>وزارة المالية</p>
            </td>
        </tr>
    </table>

    <div class="title">
        <h3>Fiche contribuable : {{ $contribuable->libelle }}</h3>
        <h3>Année {{ $annee }}</h3>
    </div>

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

    <p class="text-right"><b>Le Directeur</b></p>
</body>
</html>

