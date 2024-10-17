<?php

namespace App\Http\Controllers;

use App\Models\Ged;
use App\Models\Annee;
use App\Models\Secteur;
use App\Models\Echeance;
use App\Models\Payement;
use App\Models\Protocole;
use App\Models\RefBanque;
use App\Models\RolesAnnee;
use App\Models\MoisService;
use App\Models\Contribuable;
use App\Models\Payementmens;
use Illuminate\Http\Request;
use App\Models\RefApplication;
use App\Models\DetailsPayement;
use App\Models\RefTypepayement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RefTypesDocument;
use App\Models\RolesContribuable;
use App\Models\ContribuablesAnnee;
use Illuminate\Support\Facades\DB;
use App\Models\DetailsPayementmens;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\GardeRolesContribuable;
use App\Models\DegrevementContribuable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ContribuableRequest;


class ParamsController extends Controller
{
   




    

}