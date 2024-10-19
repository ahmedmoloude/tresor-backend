<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\Annee;
use App\Models\Contribuable;
use Illuminate\Http\Request;
use App\Models\Programmejour;
use App\Models\Programmejourcont;
use Illuminate\Support\Facades\Validator;

class ProgrammeController extends Controller
{
   
    public function getProgrammes(Request $request)
    {
    $query = Programmejour::query();

        // Apply search if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('libelle', 'like', "%{$searchTerm}%")
                  ->orWhere('date', 'like', "%{$searchTerm}%");
        }


        // with 
        $query->with('contribuables');
        // Get the current year
        $currentYear = Annee::where('etat', 1)->first()->annee ?? date('Y');

        // Filter by current year
        $query->whereYear('date', $currentYear);

        // Order by the most recent date
        $query->orderBy('date', 'desc');

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $programmes = $query->paginate($perPage);

        return response()->json($programmes);
    }


    public function addProgramme(Request $request)
    {
        $programme = Programmejour::create($request->all());
        return response()->json($programme, 201);
    }

    public function deleteProgramme($id)
    {
        $programme = Programmejour::findOrFail($id);
        $programme->delete();
        return response()->json(null, 204);
    }

    public function exportProgrammePDF($id)
    {
        $programme = Programmejour::findOrFail($id);
        $annee = Annee::where('etat', 1)->first();
        $contriProgs = Programmejourcont::where('programmejour_id', $id)
            ->with(['contribuable' => function($query) use ($annee) {
                $query->with(['rolesContribuables' => function($query) use ($annee) {
                    $query->where('annee', $annee->annee)
                        ->with('role');
                }]);
            }])
            ->get();
    
        $data = [
            'programme' => $programme,
            'annee' => $annee,
            'contriProgs' => $contriProgs,
        ];
    
        $pdf = PDF::loadView('programmes', $data);
    
        return $pdf->download("programme_{$id}.pdf");
    }

    public function addContribuablesToProgramme(Request $request, $id)
    {
        $programme = Programmejour::findOrFail($id);
        $contribuableIds = $request->input('contribuables');

        Programmejourcont::where('programmejour_id', $id)->delete();

        foreach ($contribuableIds as $contribuableId) {
            Programmejourcont::create([
                'programmejour_id' => $id,
                'contribuable_id' => $contribuableId
            ]);
        }

        return response()->json(['message' => 'Contribuables added successfully']);
    }

    public function removeContribuableFromProgramme(Request $request, $programmeId)
    {
        $programme = Programmejour::findOrFail($programmeId);
        $contribuable = Contribuable::findOrFail($request->contribuable_id);

        $programme->contribuables()->detach($contribuable);
        return response()->json(['message' => 'Contribuable removed from programme']);
    }

    public function show($id)
    {
        $programme = Programmejour::findOrFail($id);
        $contribuables = Programmejourcont::where('programmejour_id', $id)->get();
        return response()->json(['programme' => $programme, 'contribuables' => $contribuables]);
    }

    public function destroy($id)
    {
        $programme = Programmejour::findOrFail($id);
        Programmejourcont::where('programmejour_id', $programme->id)->delete();
        $programme->delete();

        return response()->json(['message' => 'Programme deleted successfully']);
    }




    public function addProgrammeWithContribuables(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string',
            'date' => 'required|date',
            'montantMinimum' => 'nullable|numeric|min:0',
            'joursDepuisDernierPaiement' => 'nullable|integer|min:0',
            'contribuables' => 'required|array|min:1',
            'contribuables.*' => 'exists:contribuables,id'
        ]);

        DB::beginTransaction();

        try {
            $programme = Programmejour::create([
                'libelle' => $request->libelle,
                'date' => $request->date,
                'montant_minimum' => $request->montantMinimum,
                'jours_depuis_dernier_paiement' => $request->joursDepuisDernierPaiement,
            ]);




            $id = $programme->id;

            foreach ($request->contribuables as $contribuableId) {
                Programmejourcont::create([
                    'programmejour_id' => $id,
                    'contribuable_id' => $contribuableId
                ]);
            }
    

            DB::commit();

            return response()->json([
                'message' => 'Programme created successfully with contribuables',
                'programme' => $programme->load('contribuables')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating programme with contribuables',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}