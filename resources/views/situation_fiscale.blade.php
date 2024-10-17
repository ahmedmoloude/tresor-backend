<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Situation Fiscale</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            border: 1px solid black;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .specfique_table td {
            border: 1px solid black;
            padding: 5px;
        }

        .page {
            width: 100%;
            max-width: 210mm;
            /* A4 width */
            margin: 0 auto;
        }

        .header-img {
            width: 100%;
            max-height: 150px;
            /* Adjust this value as needed */
            object-fit: contain;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>



<div class="page">
        <img class="header-img" src="{{ public_path('header.png') }}" alt="">


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

            
            @if ($payements->count() > 0)
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
            @endif


            @if ($payementNrs->count() > 0)
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
            @endif
        
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
    </div>

</body>

</html>