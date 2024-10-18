<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Payement CTB</title>
    <style>
        

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .page {
            width: 100%;
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
        }

        .header-img {
            width: 100%;
            max-height: 150px;
            /* Adjust this value as needed */
            object-fit: contain;
            margin-bottom: 40px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 2px solid #666;
            padding-bottom: 10px;
        }
        .filter {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            font-weight: bold;
            background-color: #e0e0e0;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
        }
        .protocols {
            margin-top: 40px;
            border-top: 2px solid #666;
            padding-top: 20px;
        }
        .protocols h2 {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>


    @php

    $restmontrecouv = 0;
        
         function contribuablePartie($id,$montantresr,$role)
    {
        $contribuable = App\Models\Contribuable::find($id);
        $impots = 'CF';
        $nbrroles=0;
        $montantdue = 0;$articles='';
        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)
        ->get();
        $degrevements = App\Models\DegrevementContribuable::where('contribuable_id', $id)->get();
        foreach ($degrevements as $deg)
        {
            $montantdue +=$deg->montant;
        }
        foreach ($roles as $role) {
            if ($role->emeregement!=''){
                $impots =$role->emeregement;
            }
        }
        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->get();
        if ($roles->count()){
            $nbrroles=1;
        }
        else{
            $nbrroles=1;
        }

        // $html = $role;
        // $montantdue = 0;
        $html='';$html1='';
        $html1 .='<td colspan="3"><table border="0" style="width: 100%;">';
        foreach ($roles as $role) {
            if ($role->emeregement!=''){

            }
            $rr=App\Models\RolesAnnee::find($role->role_id);
            $articles .= ''.$role->article .'<br> ';
            $montantdue += $role->montant;
            $html1 .='<tr>
            <td  align="left" style="width: 15%;">
            '.$role->article.'
            </td>
            <td  align="left" style="width: 60%;">
            '.$rr->libelle.'
            </td>
            <td align="right" style="width: 25%;">
            '.strrev(wordwrap(strrev($role->montant), 3, ' ', true)).'
            </td>
            </tr>';
        }
        $html1 .='</table></td>';
        $roles1 = App\Models\RolesAnnee::get();

        // $html = $role;

        $roless='';
        foreach ($roles1 as $role) {
            $rolecont = $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
            where('role_id', $role->id)->get();
            if ($rolecont->count() > 0) {
                $roless .= '' . $role->libelle . '<br> ';
            }
        }

        $montantdegr = 0;
        $motantPayes = 0;
        $annee_id = App\Models\Annee::where('etat', 1)->get()->first()->id;
        $payements = App\Models\Payement::where('contribuable_id', $id)->get();
        $payementNrs = App\Models\Payementmens::where('contribuable_id', $id)->get();
        foreach ($payements as $payement) {
            $detatPays = App\Models\DetailsPayement::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }
        foreach ($payementNrs as $payement) {
            $detatPays = App\Models\DetailsPayementmens::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }
        if ($degrevements->count()>0){
            foreach ($degrevements as $degrevement) {
                $montantdegr += $degrevement->montant;
            }
        }
        $rstap=0;
        $rstap=$montantdue -$motantPayes-$montantdegr;
        if ($contribuable and $rstap>0){
        $html .= '      <tr>
                        <td style="width: 15%;" align="center" rowspan="'.$nbrroles.'">
                            <b>'.$contribuable->libelle.' </b>
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                            '.$contribuable->adresse.'
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                           '.$impots.'
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                        </td>';
        $html .=$html1;
        $html.='
                        <td style="width:8% ;" rowspan="'.$nbrroles.'">
                            '.strrev(wordwrap(strrev($montantdue), 3, ' ', true)).'
                        </td>
                        <td style="width:10% ;" rowspan="'.$nbrroles.'">
                            '.strrev(wordwrap(strrev($montantdegr), 3, ' ', true)).'
                        </td>
                         <td style="width:10% ;" rowspan="'.$nbrroles.'">
                           '.strrev(wordwrap(strrev($rstap), 3, ' ', true)).'
                        </td>
                    </tr>
                    ';
        }
        return $html;
    }
 function situationpayement2($idpay)
    {
        $payement = App\Models\DegrevementContribuable::find($idpay);
        if ($payement){
         //   dd($payement);
        $contribuable= App\Models\Contribuable::find($payement->contribuable_id);
        $motantPayes=0;
        $html = '  <tr>
                        <td  align="center">
                            <b>'.$contribuable->libelle.'</b>
                        </td>
                        <td>
                            <b>'.$contribuable->adresse.'</b>
                        </td>
                       ';


            $html .= '
                <td >' . $payement->decision . '</td>
                <td >' . $payement->montant . '</td>
                </tr>
               ';


       return $html;
        }
    }
     function situationpayement($idpay)
    {
        $payement = App\Models\Payementmens::find($idpay);
        $role=App\Models\RolesAnnee::find($payement->role_id);
        if ($payement){
         //   dd($payement);
        $contribuable= App\Models\Contribuable::find($payement->contribuable_id);
        $detatPays = App\Models\DetailsPayementmens::where('payement_id', $idpay)->get();
        $motantPayes=0;
        $html = '  <tr>
                        <td  align="center">
                            <b>'.$contribuable->libelle.'</b>
                        </td>
                        <td>
                            <b>'.$contribuable->adresse.'</b>
                        </td>
                         <td>
                            <b>'.$role->libelle.'</b>
                        </td>
                        <td style="width:40% ;">';
        $html1 = '<table style="width: 100%;">';
        /*$html1 .= '<tr>
                <td style="width: 20%;"><b>Descript</b></td>
                <td style="width: 20%;"><b>Montant Payé</b></td>
                <td style="width: 20%;"><b>Date de paiement</b></td>
                <td style="width: 20%;"><b>N°Quittance</b></td>
                <td style="width: 20%;"><b>N°Titre</b></td>
                </tr>';*/
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;

            $html1 .= '<tr>
                <td style="width: 20%;">' . $detatPa->description . '</td>
                <td style="width: 20%;">' . $detatPa->montant . '</td>
                <td style="width: 20%;">' .Carbon\Carbon::parse($payement->date)->format('d-m-Y') . '</td>
                <td style="width: 20%;">' . $detatPa->quitance . '</td>
                <td style="width: 20%;">' . $detatPa->titre . '</td>
                </tr>';
        }
        $html1 .='</table>
        </td>
        <td>'.strrev(wordwrap(strrev($payement->montant_arriere), 3, ' ', true)).'</td>
        </tr>';
        $html .= $html1;
       return $html;
        }
    }
     function situationpayementprotocol($idpay)
    {
        $payement = App\Models\Payement::find($idpay);

        if ($payement){
         //   dd($payement);
        $contribuable= App\Models\Contribuable::find($payement->contribuable_id);
        $detatPays = App\Models\DetailsPayement::where('payement_id', $idpay)->get();
        $motantPayes=0;
        $html = '  <tr>
                        <td  align="center">
                            <b>'.$contribuable->libelle.'</b>
                        </td>
                        <td>
                            <b>'.$contribuable->adresse.'</b>
                        </td>

                        <td style="width:50% ;">';
        $html1 = '<table style="width: 100%;">';
        /*$html1 .= '<tr>
                <td style="width: 20%;"><b>Descript</b></td>
                <td style="width: 20%;"><b>Montant Payé</b></td>
                <td style="width: 20%;"><b>Date de paiement</b></td>
                <td style="width: 20%;"><b>N°Quittance</b></td>
                <td style="width: 20%;"><b>N°Titre</b></td>
                </tr>';*/
        foreach ($detatPays as $detatPa) {
            $motantPayes += $detatPa->montant;

            $html1 .= '<tr>
                <td style="width: 20%;">' . $detatPa->description . '</td>
                <td style="width: 20%;">' . $detatPa->montant . '</td>
                <td style="width: 20%;">' . Carbon\Carbon::parse($payement->date)->format('d-m-Y') . '</td>
                <td style="width: 20%;">' . $detatPa->quitance . '</td>
                <td style="width: 20%;">' . $detatPa->titre . '</td>
                </tr>';
        }
        $html1 .='</table>
        </td>
        <td>'.strrev(wordwrap(strrev($payement->montant_arriere), 3, ' ', true)).'</td>
        </tr>';
        $html .= $html1;
       return $html;
        }
    }
    @endphp


<div class="page">

        <img class="header-img" src="{{ public_path('header.png') }}" alt="">

      @if($date1 != 'all' && $date2 != 'all')
        <div class="filter">
            <strong>Filtrage :</strong><br>
            Du <b>{{ Carbon\Carbon::parse($date1)->format('d-m-Y') }}</b>
            Au <b>{{ Carbon\Carbon::parse($date2)->format('d-m-Y') }}</b>
            @if(isset($libelleRole))
                {!! $libelleRole !!}
            @endif
        </div>
    @endif

    @if($filtrage == 1)
        <table>
            <thead>
                <tr>
                    <th>Contribuable</th>
                    <th>Adresse</th>
                    <th>Rôle</th>
                    <th>Details</th>
                    <th>Reste à payer</th>
                </tr>
            </thead>
            <tbody>
                @php $montants = 0; @endphp
                @foreach($payements as $payement)
                    {!! situationpayement($payement->id) !!}
                    @php $montants += $payement->montant; @endphp
                @endforeach
            </tbody>
        </table>
        <table>
            <tr class="total">
                <td>Total</td>
                <td>{{ strrev(wordwrap(strrev($montants), 3, ' ', true)) }}</td>
            </tr>
        </table>

    @elseif($filtrage == 2)
        <table>
            <thead>
                <tr>
                    <th>Contribuable</th>
                    <th>Adresse</th>
                    <th>Decision</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @php $montants = 0; @endphp
                @foreach($payements as $payement)
                    {!! situationpayement2($payement->id) !!}
                    @php $montants += $payement->montant; @endphp
                @endforeach
            </tbody>
        </table>
        <table>
            <tr class="total">
                <td>Total</td>
                <td>{{ strrev(wordwrap(strrev($montants), 3, ' ', true)) }}</td>
            </tr>
        </table>

    @elseif($filtrage == 3)
        <table>
            <thead>
                <tr>
                    <th>Contribuable</th>
                    <th>Adresse</th>
                    <th>Impôts</th>
                    <th>Année</th>
                    <th>Article</th>
                    <th>Rôle</th>
                    <th>Montant</th>
                    <th>Montant due</th>
                    <th>Degrevement</th>
                    <th>Reste à payer</th>
                </tr>
            </thead>
            <tbody>
                @php $montants = 0; @endphp
                @foreach($contribuables as $contribuable)
                    @php
                        $roles = $role != 'all'
                            ? App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)
                                ->where('id', $role)
                                ->get()
                            : App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)
                                ->get();

                        $montantresr = $montantde = $montant_paye = $montantdgr = 0;

                        foreach ($roles as $rol) {
                            $montantde += $rol->montant;
                            $montant_paye += $rol->montant_paye;
                        }

                        $montantresr = $montantde - $montant_paye;
                    @endphp

                    @if($montantresr > 0)
                        {!! contribuablePartie($contribuable->id, $montantresr, $role) !!}
                        @php $montants += $montantresr; @endphp
                    @endif
                @endforeach
            </tbody>
        </table>
        <table>
            <tr class="total">
                <td>Total</td>
                <td>{{ strrev(wordwrap(strrev($restmontrecouv), 3, ' ', true)) }}</td>
            </tr>
        </table>
    @endif


    @if (isset($payementprotocoles))
    @if($payementprotocoles->count() > 0)
        <div class="protocols">
            <h2>Protocoles de Paiement</h2>
            <table>
                <thead>
                    <tr>
                        <th>Contribuable</th>
                        <th>Adresse</th>
                        <th>Details</th>
                        <th>Reste à payer</th>
                    </tr>
                </thead>
                <tbody>
                    @php $montantsProtocoles = 0; @endphp
                    @foreach($payementprotocoles as $payement)
                        {!! situationpayementprotocol($payement->id) !!}
                        @php $montantsProtocoles += $payement->montant; @endphp
                    @endforeach
                </tbody>
            </table>
            <table>
                <tr class="total">
                    <td>Total Protocoles</td>
                    <td>{{ strrev(wordwrap(strrev($montantsProtocoles), 3, ' ', true)) }}</td>
                </tr>
            </table>
        </div>
    @endif
    @endif
  

    <div class="signature">
        <p>Signature: _______________________</p>
        <p>Date: {{ date('d-m-Y') }}</p>
    </div>
    </div>

</body>
</html>