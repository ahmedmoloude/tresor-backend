<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation Fiscale</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid black; padding: 5px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .specfique_table td {
            border: 1px solid black;
            padding: 5px;
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

    <h3 class="text-center">Situation Fiscale</h3>

    <table class="specfique_table">
        <tr>
            <th colspan="7" bgcolor="#add8e6">Informations</th>
        </tr>
        <tr>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Impôts</th>
            <th>Année</th>
            <th>Article</th>
            <th>Rôle</th>
            <th>Montant dû</th>
        </tr>
        <tr>
            <td>{{ $contribuable->libelle }}</td>
            <td>{{ $contribuable->adresse }}</td>
            <td>{{ $impots }}</td>
            <td>{{ $annee }}</td>
            <td>
                @foreach($roles as $role)
                    {{ $role->article }}<br>
                @endforeach
            </td>
            <td>
                @foreach($roles as $role)
                    {{ $role->libelle }}<br>
                @endforeach
            </td>
            <td>{{ number_format($montantdue, 2) }}</td>
        </tr>
    </table>

    <h2>Paiements</h2>
    <table class="specfique_table">
        <tr>
            <th>Montant Payé</th>
            <th>Date de paiement</th>
            <th>N°Quittance</th>
            <th>N°Titre</th>
        </tr>
        @foreach($payements as $payement)
            @foreach($payement->detailsPayement as $detail)
                <tr>
                    <td>{{ number_format($detail->montant, 2) }}</td>
                    <td>{{ $detail->created_at }}</td>
                    <td>{{ $detail->quitance }}</td>
                    <td>{{ $detail->titre }}</td>
                </tr>
            @endforeach
        @endforeach
        @foreach($payementNrs as $payement)
            @foreach($payement->detailsPayementmens as $detail)
                <tr>
                    <td>{{ number_format($detail->montant, 2) }}</td>
                    <td>{{ $payement->date }}</td>
                    <td>{{ $detail->quitance }}</td>
                    <td>{{ $detail->titre }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

    @if($degrevements->count() > 0)
        <h2>Dégrèvements</h2>
        <table class="specfique_table">
            <tr>
                <th>Montant dégrevé</th>
                <th>N°Décision</th>
                <th>Date</th>
            </tr>
            @foreach($degrevements as $degrevement)
                <tr>
                    <td>{{ number_format($degrevement->montant, 2) }}</td>
                    <td>{{ $degrevement->decision }}</td>
                    <td>{{ $degrevement->created_at }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <p>Reste à payer = {{ number_format($restant, 2) }}</p>
    
    <p>Fait à Nouadhibou, le {{ date('d-m-Y') }}</p>
    
    <p class="text-right"><b>Signature</b></p>
</body>
</html>