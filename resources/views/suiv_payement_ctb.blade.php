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
            line-height: 1.3;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .filter {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .total {
            font-weight: bold;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>


    @php

    $restmontrecouv = 0;
        
         function contribuablePartie($id,$annee,$montantresr,$role)
    {
        $contribuable = App\Models\Contribuable::find($id);
        $impots = 'CF';
        $nbrroles=0;
        $montantdue = 0;$articles='';
        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('annee', $annee)->get();
        $degrevements = App\Models\DegrevementContribuable::where('contribuable_id', $id)->where('annee', $annee)->get();
        foreach ($degrevements as $deg)
        {
            $montantdue +=$deg->montant;
        }
        foreach ($roles as $role) {
            if ($role->emeregement!=''){
                $impots =$role->emeregement;
            }
        }
        $roles = App\Models\RolesContribuable::where('contribuable_id', $id)->
        where('annee', $annee)->get();
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
        $roles1 = App\Models\RolesAnnee::where('annee', $annee)->get();

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
        $payements = App\Models\Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
        $payementNrs = App\Models\Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();
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
                             ' . $annee . '
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
                <td style="width: 20%;">' . $payement->date . '</td>
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
                <td style="width: 20%;">' . $payement->date . '</td>
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
    @if($date1 != 'all' && $date2 != 'all')
        <div class="filter">
            <table width="100%">
                <tr><td>filtrage:</td></tr>
                <tr>
                    <td>
                    Du <b>{{ Carbon\Carbon::parse($date1)->format('d-m-Y') }}</b>
                    Au<b>{{ Carbon\Carbon::parse($date2)->format('d-m-Y') }}</b>
                        @if(isset($libelleRole))
                            {!! $libelleRole !!}
                        @endif
                    </td>
                </tr>
            </table>
        </div><br>
    @endif

    @if($filtrage == 1)
        <table width="100%"  cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" style="width:22%"><b>Contribuable</b></td>
                <td style="width:10%"><b>Adresse</b></td>
                <td style="width:18%"><b>Rôle</b></td>
                <td style="width:40%;">
                    <b>Details</b><br>
                    <table  style="width: 100%;">
                        <tr>
                            <td style="width: 20%;"><b>Descript</b></td>
                            <td style="width: 20%;"><b>Montant Payé</b></td>
                            <td style="width: 20%;"><b>Date de paiement</b></td>
                            <td style="width: 20%;"><b>N°Quittance</b></td>
                            <td style="width: 20%;"><b>N°Titre</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width:10%" align="center"><b>Reste à payer</b></td>
            </tr>
            @php $montants = 0; @endphp
            @foreach($payements as $payement)
                {!! situationpayement($payement->id) !!}
                @php $montants += $payement->montant; @endphp
            @endforeach
        </table>
        <table >
            <tr>
                <td class="total"><b>Total</b></td>
                <td align="center"><b>{{ strrev(wordwrap(strrev($montants), 3, ' ', true)) }}</b></td>
            </tr>
        </table>

        @if($payementprotocoles->count() > 0)
            <div style="page-break-after: always"></div>

        @endif

    @elseif($filtrage == 2)
        <table width="100%"  cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" style="width:30%"><b>Contribuable</b></td>
                <td style="width:15%"><b>Adresse</b></td>
                <td style="width:40%;"><b>Decision</b></td>
                <td style="width:15%"><b>Montant</b></td>
            </tr>
            @php $montants = 0; @endphp
            @foreach($payements as $payement)
                {!! situationpayement2($payement->id) !!}
                @php $montants += $payement->montant; @endphp
            @endforeach
        </table>
        <table >
            <tr>
                <td align=""><b>Total</b></td>
                <td><b>{{ strrev(wordwrap(strrev($montants), 3, ' ', true)) }}</b></td>
            </tr>
        </table>

    @elseif($filtrage == 3)
        <table width="100%"  cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 15%;" align="center"><b>Contribuable</b></td>
                <td style="width:10%;"><b>Adresse</b></td>
                <td style="width:10%;"><b>Impôts</b></td>
                <td style="width:10%;"><b>Année</b></td>
                <td style="width:5%;"><b>Article</b></td>
                <td style="width:15%;" align="center"><b>Rôle</b></td>
                <td style="width:7%;"><b>Montant</b></td>
                <td style="width:8%;"><b>Montant due</b></td>
                <td style="width:10%;"><b>Degrevement</b></td>
                <td style="width:10%;"><b>Reste à payer</b></td>
            </tr>
            @php $montants = 0; @endphp
            @foreach($contribuables as $contribuable)
                @php
                    $roles = $role != 'all'
                        ? App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)
                            ->where('annee', $annee)
                            ->where('id', $role)
                            ->get()
                        : App\Models\RolesContribuable::where('contribuable_id', $contribuable->id)
                            ->where('annee', $annee)
                            ->get();

                    $montantresr = $montantde = $montant_paye = $montantdgr = 0;

                    foreach ($roles as $rol) {
                        $montantde += $rol->montant;
                        $montant_paye += $rol->montant_paye;
                    }

                    $montantresr = $montantde - $montant_paye;
                @endphp

                @if($montantresr > 0)
                    <!-- {!! contribuablePartie($contribuable->id, $annee, $montantresr, $role) !!} -->
                    @php $montants += $montantresr; @endphp
                @endif
            @endforeach
        </table>
        <table >
            <tr>
                <td align=""><b>Total</b></td>
            </tr>
        </table>
    @endif

    <br><br>
    <table>
        <tr>
            <td align="right"><b>Signature</b></td>
        </tr>
    </table>
</body>
</html>