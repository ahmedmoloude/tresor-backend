<?php

namespace App\Http\Controllers;

use App\Models\Ged;
use App\Models\Annee;
use App\Models\Budget;
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
use Illuminate\Support\Carbon;
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


class ContribuableController extends Controller
{
    private function current_year()
    {
        return Annee::where('etat', 1)->first()->annee ?? null;
    }

    
    



    public function getAllContribuables(Request $request)
    {
        $annee = $this->current_year();
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 20);

        $query = Contribuable::query()
            ->select('contribuables.*')
            ->join('contribuables_annees', function ($join) use ($annee) {
                $join->on('contribuables.id', '=', 'contribuables_annees.contribuable_id')
                    ->where('contribuables_annees.annee', '=', $annee)
                    ->where(function ($query) {
                        $query->where('contribuables_annees.etat', '<>', 'F')
                            ->orWhereNull('contribuables_annees.etat');
                    });
            })
            ->with(['activite', 'ref_taille_activite', 'ref_emplacement_activite'])
            ->where(function ($query) use ($search) {
                $query->where('contribuables.libelle', 'ILIKE', "%$search")
                    ->orWhere('contribuables.representant', 'ILIKE', "%$search%")
                    ->orWhere('contribuables.telephone', 'ILIKE', "%$search%");
            });

        $contribuables = $query->paginate($perPage);

        $contribuablesIds = $contribuables->pluck('id')->toArray();

        // Fetch related data for all contribuables at once
        $rolesContribuables = RolesContribuable::with('role')
            ->whereIn('contribuable_id', $contribuablesIds)
            ->where('annee', $annee)
            ->get()
            ->groupBy('contribuable_id');

        $protocoles = Protocole::whereIn('contribuable_id', $contribuablesIds)
            ->get()
            ->groupBy('contribuable_id');

        $nbreRoles = $this->getNbreRoles($contribuablesIds, $annee);
        $nbreArticles = $this->getNbreArticle($contribuablesIds, $annee);
        $articles = $this->getArticles($contribuablesIds, $annee);
        $montantTotals = $this->getMontantTotal($contribuablesIds, $annee);
        $closedContribuables = $this->getClosedContribuables($contribuablesIds, $annee);

        $result = $contribuables->map(function ($contribuable) use ($annee, $rolesContribuables, $protocoles, $nbreRoles, $nbreArticles, $articles, $montantTotals, $closedContribuables) {
            return [
                'id' => $contribuable->id,
                'nbreRole' => $nbreRoles[$contribuable->id] ?? 0,
                'nbrearticle' => $nbreArticles[$contribuable->id] ?? 0,
                'article' => $articles[$contribuable->id] ?? [],
                'montant' => $montantTotals[$contribuable->id] ?? 0,
                'roles' => $rolesContribuables[$contribuable->id] ?? [],
                'protocoles' => $protocoles[$contribuable->id] ?? [],
                'libelle' => $contribuable->libelle,
                'representant' => $contribuable->representant,
                'telephone' => $contribuable->telephone,
                'adresse' => $contribuable->adresse,
                'activite' => $contribuable->activite->libelle ?? null,
                'taille_activite' => $contribuable->ref_taille_activite->libelle ?? null,
                'emplacement_activite' => $contribuable->ref_emplacement_activite->libelle ?? null,
                'is_close' => in_array($contribuable->id, $closedContribuables),
            ];
        });

        return response()->json([
            'data' => $result,
            'total' => $contribuables->total(),
            'per_page' => $contribuables->perPage(),
            'current_page' => $contribuables->currentPage(),
            'last_page' => $contribuables->lastPage(),
        ]);
    }


    private function getNbreRoles($contribuablesIds, $annee)
    {
        return RolesContribuable::whereIn('contribuable_id', $contribuablesIds)
            ->where('annee', $annee)
            ->groupBy('contribuable_id')
            ->selectRaw('contribuable_id, COUNT(*) as count')
            ->pluck('count', 'contribuable_id')
            ->toArray();
    }



    private function getClosedContribuables($contribuablesIds, $annee)
    {
        return ContribuablesAnnee::where('annee', $annee)
            ->where('etat', 'F')
            ->whereIn('contribuable_id', $contribuablesIds)
            ->pluck('contribuable_id')
            ->toArray();
    }



    public function getContribuables(Request $request)
    {
        $annee = $request->input('year') ?? $this->current_year();
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $roleId = $request->input('role_id');
        $search = $request->input('search');

        $query = Contribuable::with('activite', 'ref_taille_activite', 'ref_emplacement_activite');

        // Filter by role
        if ($roleId) {
            $query->whereIn('id', \App\Models\RolesContribuable::where('annee', $annee)
                ->where('role_id', $roleId)
                ->pluck('contribuable_id'));
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
            $contrib = \App\Models\ContribuablesAnnee::where('annee', $annee)
                ->where('etat', 'F')
                ->where('contribuable_id', $contribuable->id)
                ->get();
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
                'is_close' => $contrib->count() > 0
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



    private function updateBudgetAmounts($year, $amount, $type)
    {
        $budget = Budget::where('annee', $year)->first();


        if ($type === 'cf') {
            $budget->montant_cf = ($budget->montant_cf ?? 0) + $amount;
        } elseif ($type === 'patente') {
            $budget->montant_patente = ($budget->montant_patente ?? 0) + $amount;
        }

        $budget->save();
    }
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




        $this->updateBudgetAmounts($annee, $rowData[$columnMap['Montant']], 'cf');
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

        $this->updateBudgetAmounts($annee, $rowData[$columnMap['Montant']], 'patente');


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


        Log::info('data ..' . json_encode($data));

        $pdf = PDF::loadView('fiche_fermeture', $data);

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
        $montantTotal = (float) $montantTotal;

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
            ->where(function ($query) {
                $query->where('etat', '<>', 3)
                    ->orWhereNull('etat');
            })
            ->with('role');


        $queryPerProtocol = Payement::where('contribuable_id', $id)
            ->where(function ($query) {
                $query->where('etat', '<>', 3)
                    ->orWhereNull('etat');
            })
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




    public function getPaymentTypesData()
    {
        $paymentTypes = RefTypepayement::all();

        $banques = RefBanque::all();
        $applications = RefApplication::all();

        return response()->json([
            'paymentTypes' => $paymentTypes,
            'banques' => $banques,
            'applications' => $applications
        ]);

    }




    public function savePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:contribuables,id',
            'article' => 'required|exists:roles_contribuables,id',
            'montant' => 'required|numeric|min:0',
            'typePayement' => 'required',
            'date' => 'required|date',
            'libelle' => 'required|string',
            'libelle_ar' => 'nullable|string',
            'decision' => 'required_if:typePayement,6|string',
            'fichier' => 'nullable|file|max:50000',
            'banque' => 'nullable|string',
            'compte' => 'nullable|string',
            'numCheque' => 'nullable|string',
            'nom_app' => 'nullable|string',
            'quitance' => 'nullable|string',
            'titre' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $annee = $this->current_year();
        $montantP = str_replace(' ', '', $request->montant);
        $montantPP = (float) $montantP;
        $article = $request->article;
        $roleCont = RolesContribuable::findOrFail($article);

        DB::beginTransaction();

        try {
            if ($request->typePayement == 'Dégrèvement') {
                return $this->processDegrevementPayment($request, $roleCont, $annee, $montantPP);
            } else {
                return $this->processRegularPayment($request, $roleCont, $annee, $montantPP);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function processDegrevementPayment(Request $request, RolesContribuable $roleCont, $annee, $montantPP)
    {
        $montant = $roleCont->montant - $montantPP;
        $roleCont->montant = $montant;
        $roleCont->save();

        $degreve = DegrevementContribuable::create([
            'contribuable_id' => $request->id,
            'article_id' => $request->article,
            'annee' => $annee,
            'montant' => $montantPP,
            'decision' => $request->decision,
        ]);

        if ($degreve->id && $request->hasFile('fichier')) {
            $this->uploadDegrevementDocuments($request, $degreve);
        }

        DB::commit();
        return response()->json(['message' => 'Degrevement payment processed successfully', 'id' => $degreve->id], 200);
    }

    private function processRegularPayment(Request $request, RolesContribuable $roleCont, $annee, $montantPP)
    {
        $montant_paye = $montantPP + $roleCont->montant_paye;
        $roleCont->montant_paye = $montant_paye;
        $restmontant = $roleCont->montant - $montant_paye;
        $roleCont->save();

        $payement = Payementmens::create([
            'libelle' => $request->libelle,
            'libelle_ar' => $request->libelle_ar,
            'annee' => $annee,
            'montant_arriere' => $restmontant,
            'montant' => $montantPP,
            'etat' => 2,
            'role_id' => $roleCont->role_id,
            'contribuable_id' => $request->id,
            'date' => $request->date,
        ]);

        if ($payement->id) {
            $this->createPaymentDetails($request, $payement, $montantPP, $roleCont);
        }

        DB::commit();
        return response()->json(['message' => 'Regular payment processed successfully', 'id' => $payement->id], 200);
    }

    private function uploadDegrevementDocuments(Request $request, DegrevementContribuable $degreve)
    {
        $file = $request->file('fichier');
        $document = Ged::create([
            'libelle' => $request->decision,
            'type' => 10,
            'objet_id' => $request->id,
            // 'ref_types_document_id' => RefTypesDocument::firstWhere('libelle', 'Dégrèvement')->id,
            'type_ged' => 2,
            'emplacement' => '/courris',
            'extension' => $file->getClientOriginalExtension(),
            'taille' => $file->getSize(),
            'ordre' => ($request->filled('ordre')) ? $request->ordre : Ged::max('ordre') + 1,
        ]);

        $imageName = $document->id . '.' . $file->getClientOriginalExtension();
        $file->move(base_path() . '/public/courris', $imageName);
    }

    private function createPaymentDetails(Request $request, Payementmens $payement, $montantPP, RolesContribuable $roleCont)
    {
        DetailsPayementmens::create([
            'payement_id' => $payement->id,
            'montant' => $montantPP,
            'description' => 'Payement article ' . $roleCont->article,
            // 'mode_payement' => RefTypepayement::firstWhere( 'libelle' ,$request->typePayement)->mode_payement,
            'banque' => $request->banque,
            'compte' => $request->compte,
            'num_cheque' => $request->numCheque,
            'nom_app' => $request->nom_app,
            'quitance' => $request->quitance,
            'titre' => $request->titre,
        ]);
    }




    public function savePaymentEchance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:contribuables,id',
            'protocol' => 'required|exists:protocoles,id',
            'montant' => 'required|numeric|min:0',
            'typePayement' => 'required',
            'echeances_id' => 'required|',
            'compte' => 'nullable|string',
            'numCheque' => 'nullable|string',
            'nom_app' => 'nullable|string',
            'quitance' => 'nullable|string',
            'titre' => 'nullable|string',
            'decision' => 'required_if:typePayement,6|string',
            'fichier' => 'nullable|file|max:50000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $annee = $this->current_year();
        $montantP = str_replace(' ', '', $request->montant);
        $montantPP = (float) $montantP;

        if ($montantPP <= 0) {
            return response()->json(['error' => 'Le montant est incorrect'], 422);
        }

        $protocol = Protocole::findOrFail($request->protocol);
        $montantCash = $montantPP;
        $montant_ar = $protocol->montant_arriere - $montantCash;

        DB::beginTransaction();

        try {
            if ($request->typePayement == 'Dégrèvement') {
                return $this->processDegrevementPaymentEchance($request, $protocol, $annee, $montantPP, $montant_ar);
            } else {
                return $this->processRegularPaymentEchance($request, $protocol, $annee, $montantPP, $montant_ar);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function processDegrevementPaymentEchance(Request $request, Protocole $protocol, $annee, $montantPP, $montant_ar)
    {
        $protocol->montantdegv = $protocol->montant_arriere;
        $protocol->montant_arriere = $montant_ar;
        $montantpr = $protocol->montant - $montantPP;
        $protocol->montant = $montantpr;
        $protocol->save();

        $this->updateEcheances($request, $protocol, $montantPP);

        $degreve = DegrevementContribuable::create([
            'contribuable_id' => $request->id,
            'protocol_id' => $request->protocol,
            'annee' => $annee,
            'montant' => $montantPP,
            'decision' => $request->decision,
        ]);

        if ($degreve->id) {
            $this->uploadDegrevementDocumentsEchance($request, $degreve);
        }

        DB::commit();
        return response()->json(['message' => 'Degrevement payment processed successfully', 'id' => $degreve->id], 200);
    }

    private function processRegularPaymentEchance(Request $request, Protocole $protocol, $annee, $montantPP, $montant_ar)
    {
        $payement = Payement::create([
            'libelle' => 'Payement du protocol ' . $protocol->libelle,
            'libelle_ar' => 'Payement du protocol ' . $protocol->libelle,
            'annee' => $annee,
            'protocol_id' => $request->protocol,
            'contribuable_id' => $request->id,
            'etat' => ($montant_ar <= 0) ? 2 : null,
            'montant' => $montantPP,
            'date' => now(),
            'montant_arriere' => $montant_ar,
        ]);

        Log::info('payement' . json_encode($payement));

        if ($payement->id) {
            $this->createPaymentDetailsEchance($request, $payement, $montantPP);
            // $this->updateBudgetDetails($request, $montantPP);
            $protocol->montant_arriere = $montant_ar;
            $protocol->save();
            $this->updateEcheances($request, $protocol, $montantPP);
        }

        DB::commit();
        return response()->json(['message' => 'Regular payment processed successfully', 'id' => $payement->id], 200);
    }

    private function updateEcheances(Request $request, Protocole $protocol, $montantPP)
    {
        if ($request->echeances_id == 'all') {
            $echances = Echeance::where('protocol_id', $request->protocol)->get();
            $montantCashecheance = $montantPP;
            foreach ($echances as $echance) {
                if ($montantCashecheance > 0) {
                    $verif = $montantCashecheance - $echance->montant;
                    $echance->montantdegv = $echance->montant;
                    if ($verif >= 0) {
                        $montantCashecheance -= $echance->montant;
                        $echance->montant = 0;
                    } else {
                        $echance->montant -= $montantCashecheance;
                        $montantCashecheance = 0;
                    }
                    $echance->save();
                }
            }
        } else {
            $echan1 = Echeance::findOrFail($request->echeances_id);
            $echan1->montant = ($echan1->montant - $montantPP);
            $echan1->save();
        }
    }

    private function createPaymentDetailsEchance(Request $request, Payement $payement, $montantPP)
    {
        DetailsPayement::create([
            'payement_id' => $payement->id,
            'montant' => $montantPP,
            'description' => $payement->libelle,
            // 'mode_payement' => RefTypepayement::find($request->typePayement)->mode_payement,
            'banque' => $request->banque,
            'compte' => $request->compte,
            'num_cheque' => $request->numCheque,
            'nom_app' => $request->nom_app,
            'quitance' => $request->quitance,
            'titre' => $request->titre,
        ]);
    }

    private function updateBudgetDetails(Request $request, $montantPP)
    {
        // $role = RolesContribuable::where('contribuable_id', $request->id)
        //     ->where('annee', $this->current_year())
        //     ->first();
        // $nomenclature_element_id = RolesAnnee::find($role->role_id)->nomenclature_element_id;
        // $last_id_budget = Budget::where('annee', $this->current_year())->max('id');
        // $element = BudgetDetail::where('budget_id', $last_id_budget)
        //     ->where('nomenclature_element_id', $nomenclature_element_id)
        //     ->first();
        // $nomeMontantFinal = $element->montant_realise + $montantPP;
        // $element->montant_realise = $nomeMontantFinal;
        // $element->save();
    }

    private function uploadDegrevementDocumentsEchance(Request $request, DegrevementContribuable $degreve)
    {

        $file = $request->file('fichier');

        $document = Ged::create([
            'libelle' => $request->decision,
            'type' => 10,
            'objet_id' => $request->id,
            // 'ref_types_document_id' => 6,
            'type_ged' => 2,
            'emplacement' => '/courris',
            'extension' => $file->getClientOriginalExtension(),
            'taille' => $file->getSize(),
            'ordre' => Ged::max('ordre') + 1,
        ]);


        $imageName = $document->id . '.' . $file->getClientOriginalExtension();
        $file->move(base_path() . '/public/courris', $imageName);
    }





    public function getDashboardStats(Request $request)
    {
        $currentYear = $this->current_year();
        $year = $request->input('year', $currentYear);
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = function ($model) use ($year, $startDate, $endDate) {
            $query = $model::whereYear('created_at', $year);
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $query;
        };

        // Current period stats
        $totalDegrevements = $this->getDegrevements($query);
        $totalPayment = $this->getPayments($query);
        $totalDue = $this->getTotalDue($year);

        // Previous period stats for comparison
        $prevStartDate = $startDate ? Carbon::parse($startDate)->subMonth() : Carbon::now()->subMonth()->startOfMonth();
        $prevEndDate = $endDate ? Carbon::parse($endDate)->subMonth() : Carbon::now()->subMonth()->endOfMonth();

        $prevQuery = function ($model) use ($year, $prevStartDate, $prevEndDate) {
            return $model::whereYear('created_at', $year)
                ->whereBetween('created_at', [$prevStartDate, $prevEndDate]);
        };

        $prevDegrevements = $this->getDegrevements($prevQuery);
        $prevPayment = $this->getPayments($prevQuery);
        $prevDue = $this->getTotalDue($year, $prevEndDate);

        // Calculate percentage changes
        $degrevermentChange = $this->calculatePercentageChange($prevDegrevements, $totalDegrevements);
        $paymentChange = $this->calculatePercentageChange($prevPayment, $totalPayment);
        $totalChange = $this->calculatePercentageChange($prevDegrevements + $prevPayment, $totalDegrevements + $totalPayment);
        $restAPayerChange = $this->calculatePercentageChange($prevDue - $prevPayment, $totalDue - $totalPayment);

        $totalContribuables = Contribuable::count();
        $activeContribuables = ContribuablesAnnee::where('annee', $year)
            ->where(function ($query) {
                $query->where('etat', '<>', 'F')
                    ->orWhereNull('etat');
            })
            ->count();
        $inactiveContribuables = $totalContribuables - $activeContribuables;

        $recoveryRate = $totalDue > 0 ? $totalPayment / $totalDue : 0;

        $monthlyPayments = $this->getMonthlyPaymentsBreakdown($year, $startDate, $endDate);
        $taxDistribution = $this->getTaxDistribution($year, $startDate, $endDate);
        $paymentTrend = $this->getPaymentTrend($year, $startDate, $endDate);




        Log::info('year' . $year);

        // Calculate past year dégrevements with proper casting
        $pastYearDegrevements = DegrevementContribuable::whereYear('created_at', $year)
            ->whereRaw('CAST(annee AS INTEGER) < EXTRACT(YEAR FROM created_at)')
            ->sum(DB::raw('CAST(montant AS DECIMAL(15,2))'));

        // Calculate past year payments with proper casting
        $pastYearPayments = Payement::whereYear('date', $year)
            ->whereRaw('CAST(annee AS INTEGER) < EXTRACT(YEAR FROM date)')
            ->sum(DB::raw('CAST(montant AS DECIMAL(15,2))'));

        $pastYearPayments += Payementmens::whereYear('date', $year)
            ->whereRaw('CAST(annee AS INTEGER) < EXTRACT(YEAR FROM date)')
            ->sum(DB::raw('CAST(montant AS DECIMAL(15,2))'));
        return response()->json([
            'totalDegrevements' => $totalDegrevements,
            'totalPayment' => $totalPayment,
            'total' => $totalDegrevements + $totalPayment,
            'totalRestAPayer' => $totalDue - $totalPayment,
            'totalContribuables' => $totalContribuables,
            'activeContribuables' => $activeContribuables,
            'inactiveContribuables' => $inactiveContribuables,
            'recoveryRate' => $recoveryRate,
            'monthlyPayments' => $monthlyPayments,
            'taxDistribution' => $taxDistribution,
            'paymentTrend' => $paymentTrend,
            'degrevermentChange' => $degrevermentChange,
            'paymentChange' => $paymentChange,
            'totalChange' => $totalChange,
            'restAPayerChange' => $restAPayerChange,
            'pastYearDegrevements' => $pastYearDegrevements,
            'pastYearPayments' => $pastYearPayments,
        ]);
    }


    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        return (($newValue - $oldValue) / $oldValue) * 100;
    }

    private function getDegrevements($query)
    {
        return $query(DegrevementContribuable::class)->sum(DB::raw('CAST(montant AS DECIMAL(10,2))'));
    }

    private function getPayments($query)
    {
        $payements = $query(Payement::class)->sum(DB::raw('CAST(montant AS DECIMAL(10,2))'));
        $payementmens = $query(Payementmens::class)->sum(DB::raw('CAST(montant AS DECIMAL(10,2))'));
        return $payements + $payementmens;
    }

    private function getTotalDue($year, $endDate = null)
    {
        $query = RolesContribuable::where('annee', $year);
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        return $query->sum(DB::raw('CAST(montant AS DECIMAL(10,2))'));
    }

    private function getMonthlyPaymentsBreakdown($year, $startDate, $endDate)
    {
        $query = Payement::where('annee', $year);
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        return $query->selectRaw("EXTRACT(MONTH FROM date) as month, SUM(CAST(montant AS DECIMAL(10,2))) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    private function getTaxDistribution($year, $startDate, $endDate)
    {
        $query = RolesContribuable::where('roles_contribuables.annee', $year)
            ->join('roles_annees', 'roles_contribuables.role_id', '=', 'roles_annees.id');
        if ($startDate && $endDate) {
            $query->whereBetween('roles_contribuables.created_at', [$startDate, $endDate]);
        }
        return $query->selectRaw('roles_annees.libelle, SUM(CAST(roles_contribuables.montant AS DECIMAL(10,2))) as total')
            ->groupBy('roles_annees.id', 'roles_annees.libelle')
            ->get()
            ->pluck('total', 'libelle')
            ->toArray();
    }

    private function getPaymentTrend($year, $startDate, $endDate)
    {
        $query = Payement::where('annee', $year);
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        return $query->selectRaw("DATE(date) as date, SUM(CAST(montant AS DECIMAL(10,2))) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
    }


   





    public function getFilteredContribuables(Request $request)
    {
        $request->validate([
            'montantMinimum' => 'nullable|numeric|min:0',
            'joursDepuisDernierPaiement' => 'nullable|integer|min:0',
            'search' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'pageSize' => 'nullable|integer|min:1|max:100',
        ]);
    
        $montantMinimum = $request->input('montantMinimum');
        $joursDepuisDernierPaiement = $request->input('joursDepuisDernierPaiement');
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', 20);
        $currentYear = $this->current_year();
    
        $query = Contribuable::select('contribuables.*')
            ->selectRaw('SUM(CAST(NULLIF(roles_contribuables.montant, \'\') AS DECIMAL)) - COALESCE(SUM(CAST(NULLIF(roles_contribuables.montant_paye, \'\') AS DECIMAL)), 0) as montant_du')
            ->join('roles_contribuables', 'contribuables.id', '=', 'roles_contribuables.contribuable_id')
            ->where('roles_contribuables.annee', $currentYear)
            ->whereNull('contribuables.deleted_at')
            ->groupBy('contribuables.id');
    
        if ($montantMinimum !== null) {
            $query->havingRaw('SUM(CAST(NULLIF(roles_contribuables.montant, \'\') AS DECIMAL)) - COALESCE(SUM(CAST(NULLIF(roles_contribuables.montant_paye, \'\') AS DECIMAL)), 0) >= ?', [$montantMinimum]);
        }
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('contribuables.libelle', 'ILIKE', "%$search%")
                    ->orWhere('contribuables.montant', 'ILIKE', "%$search%");
            });
        }
    
        $totalCount = $query->count();
    
        $contribuables = $query->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->get();
    
        if ($joursDepuisDernierPaiement !== null) {
            $contribuables = $contribuables->filter(function ($contribuable) use ($joursDepuisDernierPaiement) {
                $lastPayment = $this->getLastPaymentDate($contribuable->id);
                if (!$lastPayment) {
                    return true; // Include contribuables who have never made a payment
                }
                $daysSinceLastPayment = Carbon::parse($lastPayment)->diffInDays(Carbon::now());
                return $daysSinceLastPayment >= $joursDepuisDernierPaiement;
            });
        }
    
        $result = $contribuables->map(function ($contribuable) use ($currentYear) {
            return [
                'id' => $contribuable->id,
                'nom' => $contribuable->libelle,
                'montant' => $contribuable->montant_du,
                'dernierPaiement' => $this->getLastPaymentDate($contribuable->id),
            ];
        })->values();
    
        return response()->json([
            'data' => $result,
            'total' => $totalCount,
            'current_page' => $page,
            'per_page' => $pageSize,
            'last_page' => ceil($totalCount / $pageSize),
        ]);
    }

    
    private function getLastPaymentDate($contribuableId)
    {
        $lastPayement = Payement::where('contribuable_id', $contribuableId)
            ->orderBy('date', 'desc')
            ->first();

        $lastPayementmens = Payementmens::where('contribuable_id', $contribuableId)
            ->orderBy('date', 'desc')
            ->first();

        if ($lastPayement && $lastPayementmens) {
            return $lastPayement->date > $lastPayementmens->date ? $lastPayement->date : $lastPayementmens->date;
        } elseif ($lastPayement) {
            return $lastPayement->date;
        } elseif ($lastPayementmens) {
            return $lastPayementmens->date;
        }

        return null;
    }
    private function getMontantDu($contribuableId, $annee)
    {
        $totalDu = RolesContribuable::where('contribuable_id', $contribuableId)
            ->where('annee', $annee)
            ->sum(DB::raw('CAST(montant AS DECIMAL) - COALESCE(CAST(montant_paye AS DECIMAL), 0)'));

        return $totalDu;
    }






    public function pdfSuiviPayementCtb(Request $request)
    {




        $filtrage = $request->input('filter');
        $date1 = $request->input('startDate');
        $date2 = $request->input('endDate');

        $role = 'all';
        $data = $this->prepareData($filtrage, $date1, $date2, $role);



        $pdf = PDF::loadView('suiv_payement_ctb', $data);

        return $pdf->download('suiv_payement_ctb.pdf');

    }

    private function prepareData($filtrage, $date1, $date2, $role)
    {
        $data = [
            'filtrage' => $filtrage,
            'date1' => $date1,
            'date2' => $date2,
            'role' => $role,

        ];

        switch ($filtrage) {
            case 1:
                return $this->prepareDataFiltrage1($data);
            case 2:
                return $this->prepareDataFiltrage2($data);
            case 3:
                return $this->prepareDataFiltrage3($data);
            default:
                return $data;
        }
    }


    private function prepareDataFiltrage1($data)
    {

        $query = Payement::where('montant', '<>', 0);

        if ($data['date1'] !== null) {
            $query->where('date', '>=', $data['date1']);
        }

        if ($data['date2'] !== null) {
            $query->where('date', '<=', $data['date2']);
        }

        $payementprotocoles = $query->get();

        $queryPayements = Payementmens::where('montant', '<>', 0);

        if ($data['date1'] !== null) {
            $queryPayements->where('date', '>=', $data['date1']);
        }

        if ($data['date2'] !== null) {
            $queryPayements->where('date', '<=', $data['date2']);
        }

        if ($data['role'] != 'all') {
            $queryPayements->where('role_id', $data['role']);
            $roleli = RolesAnnee::find($data['role']);
            $data['libelleRole'] = '<br>Rôle : <b>' . $roleli->libelle . '</b>';
        }

        $payements = $queryPayements->get();

        $data['payements'] = $payements;
        $data['payementprotocoles'] = $payementprotocoles;

        return $data;
    }

    private function prepareDataFiltrage2($data)
    {
        $query = DegrevementContribuable::where('montant', '<>', 0);


        if ($data['date1'] !== null) {
            $query->where('created_at', '>=', $data['date1']);
        }

        if ($data['date2'] !== null) {
            $query->where('created_at', '<=', $data['date2']);
        }

        $payements = $query->get();

        $data['payements'] = $payements;

        return $data;
    }

    private function prepareDataFiltrage3($data)
    {
        $contribuables = Contribuable::all();
        $data['contribuables'] = $contribuables;

        if ($data['role'] !== null && $data['role'] !== 'all') {
            $roleli = RolesAnnee::find($data['role']);
            if ($roleli) {
                $data['libelleRole'] = '<br>Rôle : <b>' . $roleli->libelle . '</b>';
            } else {
                $data['libelleRole'] = '<br>Rôle : <b>Non trouvé</b>';
            }
        }



        return $data;
    }

}