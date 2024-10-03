<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Procès-verbal de fermeture</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .margin-left { margin-left: 40px; }
    </style>
</head>
<body>
    <h1 class="text-center">PROCES-VERBAL DE FERMETURE, DE MAGASINS,<br>BOUTIQUES, ENTRPOTS REPRESENTATIONS<br>OU BUREAUX</h1>
    
    <p class="margin-left">L'an {{ $anneeen->description }}, le {{ date('d-m-Y') }},</p>
    
    <p class="margin-left">Nous <b>{{ $agence->libelle }}</b>, Agent de poursuite assermenté en service à la perception de <b>TR NDB</b>,</p>
    
    <p class="margin-left">
        En application de l'article L.97 du Code Général des Impôts (Loi N<sup><u>o</u></sup> 2019-18 du 29 Avril 2019)<br><br>
        avons procédé à la fermeture des magasins, boutiques ou entrepôts propriétés de M.<b>{{ $contribuable->libelle }}</b>
    </p>
    
    <p class="margin-left">
        Redevable à la perception de <b>TR NDB</b> de la somme de <b>{{ number_format($restapqye, 2) }}</b> ouguiya au titre d'impôts<br>
        <b>{{ $impots }} {{ $annee }}</b>,<br><br>
        <b>{{ $divers }}</b>
    </p>
    
    <p class="margin-left">L'Opération ci-dessus a été effectuée en présence de:</p>
    
    <table style="width: 100%; margin-top: 100px;">
        <tr>
            <td><b>LE PROPRIETAIRE</b></td>
            <td class="text-center"><b>LE REPRESENTANT<br>DE LA POLICE</b></td>
            <td class="text-right"><b>L'AGENT DE POURSUITE</b></td>
        </tr>
    </table>
</body>
</html>