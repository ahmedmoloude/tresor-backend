<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme PDF</title>
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
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        .text-right { text-align: right; }
        .header-img {
            width: 100%;
            max-height: 150px; /* Adjust this value as needed */
            object-fit: contain;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="page">
        <img class="header-img" src="{{ public_path('header.png') }}" alt="">
        
        <h2>Programme: {{ $programme->libelle }}</h2>
        <p>Date: {{ $programme->date }}</p>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Contribuable</th>
                    <th style="width: 10%;">Adresse</th>
                    <th style="width: 10%;">Impôts</th>
                    <th style="width: 10%;">Année</th>
                    <th style="width: 5%;">Article</th>
                    <th style="width: 15%;">Rôle</th>
                    <th style="width: 7%;">Montant</th>
                    <th style="width: 8%;">Montant due</th>
                    <th style="width: 10%;">Degrevement</th>
                    <th style="width: 10%;">Reste à payer</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contriProgs as $contriProg)
                    @php
                        $contribuable = $contriProg->contribuable;
                        $rolesContribuables = $contribuable->rolesContribuables->first();
                        $role = $rolesContribuables->role ?? null;
                        $montant = $rolesContribuables->montant ?? 0;
                        $montantPaye = $rolesContribuables->montant_paye ?? 0;
                        $montantDue = $montant - $montantPaye;
                        $degrevement = $rolesContribuables->degrevement ?? 0;
                        $resteAPayer = $montantDue - $degrevement;
                    @endphp
                    <tr>
                        <td>{{ $contribuable->libelle ?? '' }}</td>
                        <td>{{ $contribuable->adresse ?? '' }}</td>
                        <td>{{ $role->emeregement ?? '' }}</td>
                        <td>{{ $annee->annee ?? '' }}</td>
                        <td>{{ $rolesContribuables->article ?? '' }}</td>
                        <td>{{ $role->libelle ?? '' }}</td>
                        <td class="text-right">{{ number_format($montant, 2) }}</td>
                        <td class="text-right">{{ number_format($montantDue, 2) }}</td>
                        <td class="text-right">{{ number_format($degrevement, 2) }}</td>
                        <td class="text-right">{{ number_format($resteAPayer, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right">
            <p><strong>Signature</strong></p>
        </div>
    </div>
</body>
</html>