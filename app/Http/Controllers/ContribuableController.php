<?php

namespace App\Http\Controllers;

use App\Models\Ged;
use App\Models\Annee;
use App\Models\Secteur;
use App\Models\Document;
use App\Models\Echeance;
use App\Models\Payement;
use App\Models\Protocole;
use App\Models\RolesAnnee;
use App\Models\MoisService;
use App\Models\Contribuable;
use App\Models\Payementmens;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DetailsPayement;
use App\Models\RefTypepayement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RefTypesDocument;
use App\Models\RolesContribuable;
use Illuminate\Http\JsonResponse;
use App\Models\ContribuablesAnnee;
use Illuminate\Support\Facades\DB;

use App\Models\DetailsPayementmens;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GardeRolesContribuable;
use App\Models\DegrevementContribuable;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Http\Requests\ContribuableRequest;
use App\Http\Resources\ContribuableResource;
use Illuminate\Container\Attributes\Storage;


class ContribuableController extends Controller
{
    private function current_year()
    {
        return Annee::where('etat', 1)->first()->annee ?? null;
    }



    public function getAllContribuables(){

        $annee = $this->current_year();
        $contribuablesIds = ContribuablesAnnee::where('annee', $annee)->where('etat', '<>', 'F')->orWhereNull('etat')->pluck('contribuable_id');
        $query = Contribuable::whereIn('id', $contribuablesIds)
        ->with('activite', 'ref_taille_activite', 'ref_emplacement_activite');

        $contribuables = $query->get();


        $result = $contribuables->map(function ($contribuable) use ($annee) {
            return [
                'id' => $contribuable->id,
                'nbreRole' => $this->getNbreRole($contribuable->id, $annee),
                'nbrearticle' => $this->getNbreArticle($contribuable->id, $annee),
                'article' => $this->getArticles($contribuable->id, $annee),
                'montant' => $this->getMontantTotal($contribuable->id, $annee),
                'roles' => RolesContribuable::with('role')->where('contribuable_id',$contribuable->id)->where('annee',$annee)->get(),
                'protocoles' =>  Protocole::where('contribuable_id', $contribuable->id)->get(), 
                'libelle' => $contribuable->libelle,
                'representant' => $contribuable->representant,
                'telephone' => $contribuable->telephone,
                'adresse' => $contribuable->adresse,
                'activite' => $contribuable->activite->libelle ?? null,
                'taille_activite' => $contribuable->ref_taille_activite->libelle ?? null,
                'emplacement_activite' => $contribuable->ref_emplacement_activite->libelle ?? null,
            ];
        });

        return response()->json($result);
    }



    public function getContribuables(Request $request)
    {
        $annee = $request->input('year') ?? $this->current_year();
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $roleId = $request->input('role_id');
        $search = $request->input('search');


        $contribuablesIds = ContribuablesAnnee::where('annee', $annee)->where('etat', '<>', 'F')->orWhereNull('etat')->pluck('contribuable_id');

        $query = Contribuable::whereIn('id', $contribuablesIds)
            ->with('activite', 'ref_taille_activite', 'ref_emplacement_activite');

        // Filter by role
        if ($roleId) {
            $query->whereHas('rolesContribuables', function ($q) use ($roleId, $annee) {
                $q->where('role_id', $roleId)->where('annee', $annee);
            });
        }

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                    ->orWhere('representant', 'like', "%{$search}%")
                    ->orWhere('adresse', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }



        $query->orderBy('created_at', 'desc');


        $contribuables = $query->paginate($perPage, ['*'], 'page', $page);

        $result = $contribuables->map(function ($contribuable) use ($annee) {
            return [
                'id' => $contribuable->id,
                'nbreRole' => $this->getNbreRole($contribuable->id, $annee),
                'nbrearticle' => $this->getNbreArticle($contribuable->id, $annee),
                'article' => $this->getArticles($contribuable->id, $annee),
                'montant' => $this->getMontantTotal($contribuable->id, $annee),
                'Roles' => $this->getRoles($contribuable->id, $annee),
                'nom' => $contribuable->libelle,
                'representant' => $contribuable->representant,
                'telephone' => $contribuable->telephone,
                'adresse' => $contribuable->adresse,
                'activite' => $contribuable->activite->libelle ?? null,
                'taille_activite' => $contribuable->ref_taille_activite->libelle ?? null,
                'emplacement_activite' => $contribuable->ref_emplacement_activite->libelle ?? null,
            ];
        });

        return response()->json([
            'data' => $result,
            'current_page' => $contribuables->currentPage(),
            'last_page' => $contribuables->lastPage(),
            'per_page' => $contribuables->perPage(),
            'total' => $contribuables->total(),
        ]);
    }
    private function getNbreRole($contribuableId, $annee)
    {
        $roles = RolesAnnee::where('annee', $annee)->get();
        return $roles->filter(function ($role) use ($contribuableId) {
            return RolesContribuable::where('contribuable_id', $contribuableId)
                ->where('role_id', $role->id)
                ->exists();
        })->count();
    }

    private function getNbreArticle($contribuableId, $annee)
    {
        return RolesContribuable::where('contribuable_id', $contribuableId)
            ->where('annee', $annee)
            ->count();
    }


    private function getArticles($contribuableId, $annee)
    {
        $roles = RolesContribuable::where('contribuable_id', $contribuableId)
            ->where('annee', $annee)
            ->get();


        Log::info('roles ...' . json_encode($roles));
        return $roles->pluck('article')->implode(' / ');
    }

    private function getMontantTotal($contribuableId, $annee)
    {
        return RolesContribuable::where('contribuable_id', $contribuableId)
            ->where('annee', $annee)
            ->sum(DB::raw('CAST(montant AS DECIMAL(10,2))'));
    }

    private function getRoles($contribuableId, $annee)
    {
        $roles = RolesContribuable::where('contribuable_id', $contribuableId)
            ->where('annee', $annee)
            ->get();
        return $roles->map(function ($role) {
            $libelleRole = RolesAnnee::find($role->role_id)->libelle;
            return $libelleRole . ' :' . $role->montant;
        })->implode(' ');
    }

    public function store(ContribuableRequest $request)
    {
        DB::beginTransaction();
        try {


            $annee = $this->current_year();




            $montantGlobal = collect($request->input('details'))->sum('money');


            $contribuable = $this->createContribuable($request, $annee, $montantGlobal);



            $this->createRelatedRecords($contribuable, $request, $annee);

            // Step 2: Handle the 'details' array (money and role)
            $details = $request->input('details', []);



            Log::info('details' . json_encode($details));


            foreach ($details as $detail) {
                RolesContribuable::create([

                    'contribuable_id' => $contribuable->id,
                    'montant' => $detail['money'],
                    'role_id' => $detail['role'],
                    'annee' => $this->current_year(),
                    'article' => 'SP' . $contribuable->id
                ]);
            }

            DB::commit();

            return response()->json(['id' => $contribuable->id, 'message' => 'Contribuable created successfully'], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating contribuable: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error creating contribuable.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function createContribuable(ContribuableRequest $request, int $annee, $montant): Contribuable
    {
        return Contribuable::create([
            'libelle' => $request->nom,
            'representant' => $request->representant,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'montant' => $montant,
            'date_mas' => $request->date_mas,
            'article' => $this->getNextArticleNumber(),
            'etat' => 1,

            //TODO: to be verified 
            'activite_id' => 1,
            'ref_emplacement_activite_id' => 1,
            'ref_taille_activite_id' => 1,
        ]);
    }

    private function createRelatedRecords(Contribuable $contribuable, ContribuableRequest $request, $annee): void
    {
        $this->createMoisService($contribuable, $request, $annee);
        $this->createContribuablesAnnee($contribuable, $request, $annee);
    }

    private function createMoisService(Contribuable $contribuable, ContribuableRequest $request, $annee): void
    {
        $month = date("n", strtotime($request->date_mas));
        $day = date("j", strtotime($request->date_mas));
        if ($day > 15) {
            $month++;
        }

        Log::info('annee ' . $annee);


        MoisService::create([
            // 'mois_id' => $month,
            'annee' => $annee,
            'contribuable_id' => $contribuable->id
        ]);
    }

    private function createContribuablesAnnee(Contribuable $contribuable, ContribuableRequest $request, $annee): void
    {
        ContribuablesAnnee::create([
            'annee' => $annee,
            'contribuable_id' => $contribuable->id,
            'montant' => $request->montant,
            'spontane' => 1
        ]);
    }




    private function getNextArticleNumber(): string
    {
        $maxId = Contribuable::max('id') ?? 0;
        return 'SP' . ($maxId + 1);
    }



    public function getAllRoles()
    {

        $annee = Annee::where('etat', 1)->get()->first();

        $roles = RolesAnnee::where('annee', $annee->annee)->where('etat', '<>', 2)->get();
        return response()->json($roles);
    }








    /**
     * Process an Excel file and create the corresponding records in the database.
     * The file should have the following columns:
     * - Article: the article number
     * - Libell : the article name
     * - Montant: the amount
     * - Date de naissance: the birth date
     * - Adresse: the address
     * - Telephone: the phone number
     * - Reference: the reference
     * - Nbre de role: the number of roles
     *
     * The request should include the file and the role type (rolecf or rolePATENTE).
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */


    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'role_type' => 'required|in:rolecf,rolePATENTE',
        ]);

        DB::beginTransaction();

        try {
            $file = $request->file('file');

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            $role = RolesAnnee::where('etat', 1)->firstOrFail();
            $role_id = $role->id;

            $cp = $cpenr = 0;
            $headerRow = $worksheet->getRowIterator()->current();
            $cellIterator = $headerRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $headers = [];
            foreach ($cellIterator as $cell) {
                $headers[] = $cell->getValue();
            }

            $columnMap = array_flip($headers);

            foreach ($worksheet->getRowIterator() as $row) {
                if ($row->getRowIndex() === 1) {
                    continue; // Skip header row
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $result = $this->processRow($rowData, $role_id, $request->role_type, $columnMap);
                $cp += $result['cp'];
                $cpenr += $result['cpenr'];
            }

            DB::commit();

            return response()->json([
                'message' => 'File processed successfully',
                'total_rows' => $cp + $cpenr,
                'processed_rows' => $cpenr,
                'skipped_rows' => $cp,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function processRow($rowData, $role_id, $roleType, $columnMap)
    {
        $cp = $cpenr = 0;
        $annee = Annee::where('etat', 1)->first()->annee;

        if ($roleType === 'rolecf') {
            $contribuable = $this->processRoleCF($rowData, $annee, $columnMap);
        } else {
            $contribuable = $this->processRolePATENTE($rowData, $annee, $columnMap);
        }

        MoisService::firstOrCreate([
            //  'mois_id' => 1,
            'annee' => $annee,
            'contribuable_id' => $contribuable->id,
        ]);

        ContribuablesAnnee::firstOrCreate([
            'annee' => $annee,
            'contribuable_id' => $contribuable->id,
        ]);

        $existingRole = RolesContribuable::where('contribuable_id', $contribuable->id)
            ->where('article', trim($rowData[$columnMap['Article']]))
            ->where('role_id', $role_id)
            ->where('annee', $annee)
            ->first();

        if ($existingRole) {
            GardeRolesContribuable::create([
                'contribuable_id' => $contribuable->id,
                'role_id' => $role_id,
                'annee' => $annee,
                'anneerel' => $rowData[$columnMap['Année']],
                'montant' => $rowData[$columnMap['Montant']],
                'periode' => $rowData[$columnMap['Période']],
                'emeregement' => 'Repetition',
                'article' => $rowData[$columnMap['Article']],
            ]);
            $cp++;
        } else {



            // Log::info('Contribuable' . $contribuable->id);




            RolesContribuable::create([
                'contribuable_id' => $contribuable->id,
                'role_id' => $role_id,
                'annee' => $annee,
                'anneerel' => $rowData[$columnMap['Année']],
                'montant' => $rowData[$columnMap['Montant']],
                'periode' => $rowData[$columnMap['Période']],
                'adresses' => trim($rowData[$columnMap['Adresse']]),
                'emeregement' => $rowData[$columnMap['Rôle']],
                'article' => $rowData[$columnMap['Article']],
            ]);
            $cpenr++;
        }

        return ['cp' => $cp, 'cpenr' => $cpenr];
    }

    private function processRoleCF($rowData, $annee, $columnMap)
    {
        return Contribuable::firstOrCreate(
            ['libelle' => $rowData[$columnMap['Contribuable']]],
            [
                'libelle_ar' => $rowData[$columnMap['Contribuable']],
                'representant' => $rowData[$columnMap['Contribuable']],
                'adresse' => $rowData[$columnMap['Adresse']],
                'activite_id' => 2,
                'article' => $rowData[$columnMap['Article']],
                'montant' => $rowData[$columnMap['Montant']],
                'ref_emplacement_activite_id' => 1,
                'ref_taille_activite_id' => 1,
            ]
        );
    }

    private function processRolePATENTE($rowData, $annee, $columnMap)
    {
        return Contribuable::firstOrCreate(
            ['nif' => $rowData[$columnMap['Article']]],
            [
                'libelle' => $rowData[$columnMap['Contribuable']],
                'libelle_ar' => $rowData[$columnMap['Contribuable']],
                'representant' => $rowData[$columnMap['Contribuable']],
                'adresse' => $rowData[$columnMap['Adresse']],
                'activite_id' => 2,
                'article' => $rowData[$columnMap['Article']],
                'montant' => $rowData[$columnMap['Montant']],
                'ref_emplacement_activite_id' => 1,
                'ref_taille_activite_id' => 1,
            ]
        );
    }



    public function getTaxPayerDetails($id)
    {
        $contribuale = Contribuable::findOrFail($id);
        $annee = $this->current_year();
        $annee_id = Annee::where('etat', 1)->first()->id;
        $protocoleEchencess = Protocole::where('contribuable_id', $id)
            ->where('annee_id', $annee_id)
            ->get();
        $nbrproEchen = $protocoleEchencess->count();

        return response()->json([
            'contribuale' => $contribuale,
            'nbrproEchen' => $nbrproEchen
        ]);
    }






    public function getTaxPayerProtocols($id, Request $request)
    {
        $selected = $request->input('selected', 'all');
        $query = Protocole::where('contribuable_id', $id);

        if ($selected != 'all') {
            $query->orderByRaw('id = ? desc', [$selected]);
        }

        $protocols = $query->get();

        return response()->json($protocols);
    }


    public function getTaxPayerFiscalSituation($id, $ann)
    {
        $contribuable = Contribuable::findOrFail($id);
        $annee = Annee::findOrFail($ann)->annee;

        $roles = RolesContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)
            ->get();

        $degrevements = DegrevementContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)
            ->get();

        $payements = Payement::where('contribuable_id', $id)
            ->where('annee', $annee)
            ->with('detailsPayement')
            ->get();

        $payementNrs = Payementmens::where('contribuable_id', $id)
            ->where('annee', $annee)
            ->with('detailsPayementmens')
            ->get();

        return response()->json([
            'contribuable' => $contribuable,
            'roles' => $roles,
            'degrevements' => $degrevements,
            'payements' => $payements,
            'payementNrs' => $payementNrs
        ]);
    }



    public function exportcontribuablePDF($id)
    {
        $contribuable = Contribuable::findOrFail($id);
        $annee = $this->current_year();
        $annee_id = Annee::where('etat', 1)->first()->id;
        $protocoles = Protocole::where('contribuable_id', $id)
            ->where('annee_id', $annee_id)
            ->get();

        $montantsgen = 0;
        foreach ($protocoles as $protocole) {
            $payements = Payement::where('contribuable_id', $id)
                ->where('protocol_id', $protocole->id)
                ->where('annee', $annee)
                ->get();

            foreach ($payements as $p) {
                $montantsgen += $p->montant;
            }
        }

        $data = [
            'contribuable' => $contribuable,
            'annee' => $annee,
            'protocoles' => $protocoles,
            'montantsgen' => $montantsgen,
        ];

        $pdf = PDF::loadView('contribuable_fiche', $data);


        $pdf->autoScriptToLang = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;



        return $pdf->download('contribuable_fiche.pdf');
    }




    public function sutiationcontribuablePDF($id)
    {
        $contribuable = Contribuable::findOrFail($id);
        $annee = $this->current_year();
        $roles = RolesContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)->get();
        $degrevements = DegrevementContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)->get();

        $impots = 'CF';
        $montantdue = 0;
        foreach ($roles as $role) {
            if ($role->emeregement != '') {
                $impots = $role->emeregement;
            }
            $montantdue += $role->montant;
        }

        $montantdegr = 0;
        $motantPayes = 0;
        $payements = Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
        $payementNrs = Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();

        foreach ($payements as $payement) {
            $detatPays = DetailsPayement::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }

        foreach ($payementNrs as $payement) {
            $detatPays = DetailsPayementmens::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }

        foreach ($degrevements as $degrevement) {
            $montantdegr += $degrevement->montant;
        }

        $data = [
            'contribuable' => $contribuable,
            'annee' => $annee,
            'impots' => $impots,
            'roles' => $roles,
            'montantdue' => $montantdue,
            'payements' => $payements,
            'payementNrs' => $payementNrs,
            'degrevements' => $degrevements,
            'motantPayes' => $motantPayes,
            'montantdegr' => $montantdegr,
            'restant' => $montantdue - $motantPayes - $montantdegr,
        ];

        $pdf = PDF::loadView('situation_fiscale', $data);

        return $pdf->download('situation_fiscale_' . $contribuable->libelle . '.pdf');
    }



    public function fichdefermercontribuable($id)
    {
        $contribuable = Contribuable::findOrFail($id);
        $anneeen = Annee::where('etat', 1)->firstOrFail();
        $annee = $anneeen->annee;
        $agence = Secteur::where('ordre', 1)->first();

        $roles = RolesContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)->get();

        $impots = 'CONTRIBUTION FONCIERE';
        $divers = '';
        $articles = '';

        foreach ($roles as $role) {
            if ($role->emeregement != '') {
                $impots = $role->emeregement;
            }
            $articles .= $role->article . '<br>';
        }

        if ($roles->count() > 1) {
            $divers = 'Divers Articles';
        } elseif ($roles->count() > 0) {
            $role = RolesAnnee::find($roles->first()->role_id);
            $divers = 'Article ' . $roles->first()->article . ' / ' . $role->libelle . ' / EX ' . $annee;
        }

        $restapqye = $this->contribuableRestApaye($id, $annee);

        $data = [
            'contribuable' => $contribuable,
            'annee' => $annee,
            'anneeen' => $anneeen,
            'agence' => $agence,
            'impots' => $impots,
            'divers' => $divers,
            'articles' => $articles,
            'restapqye' => $restapqye,
        ];

        $pdf = PDF::loadView('pdf.fiche_fermeture', $data);

        return $pdf->download('fiche_fermeture_' . $contribuable->libelle . '.pdf');
    }

    public function contribuableRestApaye($id, $annee)
    {
        $contribuable = Contribuable::find($id);
        $impots = 'CF';
        $montantdue = 0;
        $articles = '';
        $roles = RolesContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)->get();
        $degrevements = DegrevementContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)->get();

        foreach ($degrevements as $deg) {
            $montantdue += $deg->montant;
        }

        foreach ($roles as $role) {
            if ($role->emeregement != '') {
                $impots = $role->emeregement;
            }
            $rr = RolesAnnee::find($role->role_id);
            $articles .= $role->article . '<br>';
            $montantdue += $role->montant;
        }

        $montantdegr = 0;
        $motantPayes = 0;
        $payements = Payement::where('contribuable_id', $id)->where('annee', $annee)->get();
        $payementNrs = Payementmens::where('contribuable_id', $id)->where('annee', $annee)->get();

        foreach ($payements as $payement) {
            $detatPays = DetailsPayement::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }

        foreach ($payementNrs as $payement) {
            $detatPays = DetailsPayementmens::where('payement_id', $payement->id)->get();
            foreach ($detatPays as $detatPa) {
                $motantPayes += $detatPa->montant;
            }
        }

        if ($degrevements->count() > 0) {
            foreach ($degrevements as $degrevement) {
                $montantdegr += $degrevement->montant;
            }
        }

        $rstap = $montantdue - $motantPayes - $montantdegr;

        return $rstap;
    }


    public function closeContribuable($id)
    {
        $annee = $this->current_year();

        ContribuablesAnnee::where('annee', $annee)->where('contribuable_id', $id)->update(['etat' => 'F']);
        return response()->json(['result' => 'ok']);
    }



    public function update(ContribuableRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $contribuable = Contribuable::findOrFail($id);
            $annee = $this->current_year();

            // Update contribuable basic information
            $contribuable->update([
                'libelle' => $request->nom,
                'representant' => $request->representant,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'date_mas' => $request->dateMiseEnService,
                'montant' => $request->montant
            ]);

            // Update or create ContribuablesAnnee record
            ContribuablesAnnee::updateOrCreate(
                ['annee' => $annee, 'contribuable_id' => $contribuable->id],
                ['montant' => $request->montant]
            );

            // Update MoisService
            // $month = date("n", strtotime($request->date_mas));
            // $day = date("j", strtotime($request->date_mas));
            // if ($day > 15) {
            //     $month++;
            // }
            // MoisService::updateOrCreate(
            //     ['annee' => $annee, 'contribuable_id' => $contribuable->id],
            //     ['mois_id' => $month]
            // );

            // Update RolesContribuable records
            // RolesContribuable::where('contribuable_id', $contribuable->id)
            //     ->where('annee', $annee)
            //     ->delete();

            // foreach ($request->input('details', []) as $detail) {
            //     RolesContribuable::create([
            //         'contribuable_id' => $contribuable->id,
            //         'montant' => $detail['money'],
            //         'role_id' => $detail['role'],
            //         'annee' => $annee,
            //         'article' => 'SP' . $contribuable->id
            //     ]);
            // }

            DB::commit();

            return response()->json([
                'message' => 'Contribuable updated successfully',
                'contribuable' => $contribuable
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating contribuable: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error updating contribuable.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



   

    public function createProtocol(Request $request, $id)
{
    $request->validate([
        // 'date_echeance' => 'required|date',
        'description' => 'required|string',
        'montant' => 'required|numeric',
        'nombre_echeance' => 'required|integer|min:1|max:5',
        'observation' => 'nullable|string',
        'echeances' => 'array',
        'echeances.*.date_echeance' => 'required|date',
        'echeances.*.montant' => 'required|numeric',
    ]);


    $annee = $this->current_year();
    $annee_id = Annee::where('etat', 1)->first()->id;

    $montantTotal = str_replace(' ', '', $request->montant);
    $montantTotal = (float)$montantTotal;

    // Verify if the sum of echéances equals the total amount
    $montantEcheances = collect($request->echeances)->sum('montant');


    if ($montantEcheances != $montantTotal) {
        return response()->json(['errors' => ['montant' => ['Les montants des échéances ne correspondent pas au montant total']]], 422);
    }

    DB::beginTransaction();

    try {
        $protocol = new Protocole([
            'libelle' => $request->description,
            'annee_id' => $annee_id,
            'contribuable_id' => $id,
            'montant' => $montantTotal,
            // 'dateEch' => $request->date_echeance,
            'remarque' => $request->observation,
            'montant_arriere' => $montantTotal,
            'nb_echeance' => $request->nombre_echeance,
        ]);

        $protocol->save();

        // Create echéances


        foreach ($request->echeances as $echeance) {
            $echeance = new Echeance([
                'protocol_id' => $protocol->id,
                'dateEch' => $echeance['date_echeance'],

                'montant' => $echeance['montant'],
            ]);
            $echeance->save();
        }

        // Update RolesContribuable
        $roles = RolesContribuable::where('contribuable_id', $id)
            ->where('annee', $annee)
            ->get();

        foreach ($roles as $role) {
            $role->protocole_id = $protocol->id;
            $role->save();
        }

        DB::commit();

        return response()->json([
            'message' => 'Protocol created successfully',
            'protocol' => $protocol
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'message' => 'Error creating protocol',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function updateProtocol(Request $request, $id)
    {
        $protocol = Protocole::findOrFail($id);

        $request->validate([
            'description' => 'required|string',
            'montant' => 'required|numeric',
            'date_echeance' => 'required|date',
            'nombre_echeance' => 'required|integer',
            'observation' => 'nullable|string',
        ]);

        $protocol->update([
            'libelle' => $request->description,
            'montant' => $request->montant,
            'dateEch' => $request->date_echeance,
            'nb_echeance' => $request->nombre_echeance,
            'observation' => $request->observation,
        ]);

        return response()->json(['message' => 'Protocol updated successfully', 'protocol' => $protocol]);
    }

    public function deleteProtocol($id)
    {
        $protocol = Protocole::findOrFail($id);
        $protocol->delete();

        return response()->json(['message' => 'Protocol deleted successfully']);
    }

    public function getTaxPayerPayments($id, Request $request)
    {
        $selected = $request->input('selected', 'all');
        $queryPerArticle = Payementmens::where('contribuable_id', $id)
            ->where('etat', '<>', 3)
            ->with('role');

        $queryPerProtocol = Payement::where('contribuable_id', $id)
            ->where('etat', '<>', 3)
            ->with('protocol');

        if ($selected != 'all') {
            $queryPerArticle->orderByRaw('id = ? desc', [$selected]);
            $queryPerProtocol->orderByRaw('id = ? desc', [$selected]);
        }

        $paymentsPerArticle = $queryPerArticle->get();
        $paymentsPerProtocol = $queryPerProtocol->get();

        return response()->json([
            'perArticle' => $paymentsPerArticle,
            'perProtocol' => $paymentsPerProtocol
        ]);
    }

    public function createPayment(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'montant' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:article,protocol',
            'role_id' => 'required_if:type,article',
            'protocol_id' => 'required_if:type,protocol',
        ]);

        DB::beginTransaction();

        try {
            if ($request->type === 'article') {
                $payment = new Payementmens([
                    'contribuable_id' => $id,
                    'libelle' => $request->description,
                    'montant' => $request->montant,
                    'date' => $request->date,
                    'role_id' => $request->role_id,
                ]);
            } else {
                $payment = new Payement([
                    'contribuable_id' => $id,
                    'libelle' => $request->description,
                    'montant' => $request->montant,
                    'date' => $request->date,
                    'protocol_id' => $request->protocol_id,
                ]);
            }

            $payment->save();
            DB::commit();

            return response()->json(['message' => 'Payment created successfully', 'payment' => $payment]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating payment', 'error' => $e->getMessage()], 500);
        }
    }



    public function getTaxPayerDocuments($id)
    {
        $documents = Ged::where(['objet_id' => $id, 'type_ged' => 2, 'type' => 10])
            ->with(['ref_types_document'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($documents);
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required|string',
            'ref_types_documents_id' => 'required',
            'fichier' => 'required|file|max:10240', // 10MB max
        ]);

        $gedExist = Ged::where(['objet_id' => $id, 'libelle' => $request->libelle])->exists();
        if ($gedExist) {
            return response()->json(['errors' => ['libelle' => [trans('text_my.liblle_existe')]]], 422);
        }

        $file = $request->file('fichier');
        $document = new Ged();
        $document->libelle = $request->libelle;
        $document->type = 10; // Assuming 10 is for contribuables
        $document->objet_id = $id;
        $document->ref_types_document_id = $request->ref_types_documents_id;
        $document->type_ged = 2;
        $document->emplacement = '/files';
        $document->extension = $file->getClientOriginalExtension();
        $document->taille = $file->getSize();
        $document->ordre = Ged::max('ordre') + 1;
        $document->save();

        $fileName = $document->id . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('/files'), $fileName);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document
        ]);
    }

    public function deleteDocument($id)
    {
        $document = Ged::findOrFail($id);
        $filePath = public_path($document->emplacement . '/' . $document->id . '.' . $document->extension);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }

    public function getDocumentTypes()
    {
        $types = RefTypesDocument::all();
        return response()->json($types);
    }




    public function getPaymentTypes(){
        $response  =  RefTypepayement::all();

        return response()->json($response);

    }
}