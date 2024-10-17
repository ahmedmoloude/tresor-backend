<?php

namespace App\Http\Controllers;

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
        $idc = env('APP_COMMUNE');
    
        $programme = Programmejour::findOrFail($id);
        $annee = Annee::where('etat', 1)->first();
        $contriProgs = Programmejourcont::where('programmejour_id', $id)->get();
    
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

}