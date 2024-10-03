<?php

namespace App\Models;

use Elibyy\TCPDF\Facades\TCPDF;

class MyPDF extends TCPDF
{

    function test()
    {
        $this::SetTitle('Sidi Maarouf');
    }
    function myEach(&$arr) {
        $key = key($arr);
        $result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
        next($arr);
        return $result;
    }

    function addLine( $ligne, $tab,$colonnes,$ordonnee,$soul=false )
    {
        global  $format;


        $maxSize      = $ligne;

        reset( $colonnes );
        while ( list( $lib, $pos ) = $this->myEach($colonnes) )
        {
            $longCell  = $pos -2;
            $texte     = $tab[ $lib ];
            $length    = $this::GetStringWidth( $texte );
            $tailleTexte = $this->sizeOfText( $texte, $length );
            $formText  = $format[ $lib ];
            $this::SetXY( $ordonnee, $ligne-1);
            $this::MultiCell( $longCell, 4 , $texte, 0, $formText);
            if($soul != false)
            {
                //$this->Cell($longCell,1,'4',1,0,'R');
                //$this->Rect($ordonnee, 0, $longCell, $maxSize, 'D');
            }
            if ( $maxSize < ($this::GetY()  ) )
                $maxSize = $this::GetY() ;
            $ordonnee += $pos;
        }
        return ( $maxSize - $ligne );
    }


    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this::k,$white*$this::k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

    function sizeOfText( $texte, $largeur )
    {
        $index    = 0;
        $nb_lines = 0;
        $loop     = TRUE;
        while ( $loop )
        {
            $pos = strpos($texte, "\n");
            if (!$pos)
            {
                $loop  = FALSE;
                $ligne = $texte;
            }
            else
            {
                $ligne  = substr( $texte, $index, $pos);
                $texte = substr( $texte, $pos+1 );
            }
            $length = floor( $this::GetStringWidth( $ligne ) );
            $res = 1 + floor( $length / ($largeur > 0 ? $largeur:1)) ;
            $nb_lines += $res;
        }
        return $nb_lines;
    }
    function addLineFormat( $tab )
    {
        global $format, $colonnes;

        while ( list( $lib, $pos ) = each ($colonnes) )
        {
            if ( isset( $tab["$lib"] ) )
                $format[ $lib ] = $tab["$lib"];
        }
    }
}
