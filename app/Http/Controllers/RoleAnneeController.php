<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\RolesAnnee;
use App\Models\ForchetteTax;
use Illuminate\Http\Request;
use App\Models\RefTailleActivite;
use App\Models\NomenclatureElement;
use App\Http\Controllers\Controller;
use App\Models\RefCategorieActivite;
use App\Models\RefEmplacementActivite;
use Illuminate\Support\Facades\Validator;

class RoleAnneeController extends Controller
{
    public function index()
    {
        $roles = RolesAnnee::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'nomenclature_element_id' => 'required|exists:nomenclature_elements,id',
            'annee' => 'required|integer',
            'etat' => 'required|boolean',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = RolesAnnee::create($request->all());
        return response()->json($role, 201);
    }

    public function show($id)
    {
        $role = RolesAnnee::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = RolesAnnee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'nomenclature_element_id' => 'required|exists:nomenclature_elements,id',
            'annee' => 'required|integer',
            'etat' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $role->update($request->all());
        return response()->json($role);
    }


    public function destroy($id)
    {
        $role = RolesAnnee::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }

    public function getNomenclatures()
    {
        $nomenclatures = NomenclatureElement::where('niveau', '<>', 1)
            ->where('ref_type_nomenclature_id', 1)
            ->get();

        return response()->json($nomenclatures);

    }


    public function indexActivites()
    {
        $activites = Activite::with('ref_categorie_activite')->get();
        return response()->json($activites);
    }

    public function storeActivite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'libelle_ar' => 'required|string|max:255',
            'ref_categorie_activite_id' => 'required|exists:ref_categorie_activites,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $activite = Activite::create($request->all());
        return response()->json($activite, 201);
    }

    public function showActivite($id)
    {
        $activite = Activite::with('ref_categorie_activite')->findOrFail($id);
        return response()->json($activite);
    }

    public function updateActivite(Request $request, $id)
    {
        $activite = Activite::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'libelle_ar' => 'required|string|max:255',
            'ref_categorie_activite_id' => 'required|exists:ref_categorie_activites,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $activite->update($request->all());
        return response()->json($activite);
    }

    public function destroyActivite($id)
    {
        $activite = Activite::findOrFail($id);
        $activite->delete();
        return response()->json(null, 204);
    }

    // Tax Parameters (Forchets)
    public function indexForchets()
    {
        $forchets = ForchetteTax::with('ref_categorie_activite', 'ref_emplacement_activite', 'ref_taille_activite')->get();
        return response()->json($forchets);
    }

    public function storeForchet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ref_categorie_activite_id' => 'required|exists:ref_categorie_activites,id',
            'ref_emplacement_activite_id' => 'required|exists:ref_emplacement_activites,id',
            'ref_taille_activite_id' => 'required|exists:ref_taille_activites,id',
            'montant' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (ForchetteTax::where('ref_categorie_activite_id', $request->ref_categorie_activite_id)
            ->where('ref_emplacement_activite_id', $request->ref_emplacement_activite_id)
            ->where('ref_taille_activite_id', $request->ref_taille_activite_id)
            ->exists()) {
            return response()->json(['error' => trans('text_me.taxe_existe')], 422);
        }

        $forchet = ForchetteTax::create($request->all());
        return response()->json($forchet, 201);
    }

    public function showForchet($id)
    {
        $forchet = ForchetteTax::with('ref_categorie_activite', 'ref_emplacement_activite', 'ref_taille_activite')->findOrFail($id);
        return response()->json($forchet);
    }

    public function updateForchet(Request $request, $id)
    {
        $forchet = ForchetteTax::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ref_categorie_activite_id' => 'required|exists:ref_categorie_activites,id',
            'ref_emplacement_activite_id' => 'required|exists:ref_emplacement_activites,id',
            'ref_taille_activite_id' => 'required|exists:ref_taille_activites,id',
            'montant' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (ForchetteTax::where('ref_categorie_activite_id', $request->ref_categorie_activite_id)
            ->where('ref_emplacement_activite_id', $request->ref_emplacement_activite_id)
            ->where('ref_taille_activite_id', $request->ref_taille_activite_id)
            ->where('id', '!=', $id)
            ->exists()) {
            return response()->json(['error' => trans('text_me.taxe_existe')], 422);
        }

        $forchet->update($request->all());
        return response()->json($forchet);
    }

    public function destroyForchet($id)
    {
        $forchet = ForchetteTax::findOrFail($id);
        $forchet->delete();
        return response()->json(null, 204);
    }

    // Categories
    public function indexCategories()
    {
        $categories = RefCategorieActivite::all();
        return response()->json($categories);
    }

    public function storeCategorie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'libelle_ar' => 'required|string|max:255',
            'montant' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categorie = RefCategorieActivite::create([
            'libelle' => $request->libelle,
            'libelle_ar' => $request->libelle_ar,
            'montant' => (float) str_replace(' ', '', $request->montant),
        ]);

        return response()->json($categorie, 201);
    }

    public function showCategorie($id)
    {
        $categorie = RefCategorieActivite::findOrFail($id);
        return response()->json($categorie);
    }

    public function updateCategorie(Request $request, $id)
    {
        $categorie = RefCategorieActivite::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'libelle_ar' => 'required|string|max:255',
            'montant' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categorie->update([
            'libelle' => $request->libelle,
            'libelle_ar' => $request->libelle_ar,
            'montant' => (float) str_replace(' ', '', $request->montant),
        ]);

        return response()->json($categorie);
    }

    public function destroyCategorie($id)
    {
        $categorie = RefCategorieActivite::findOrFail($id);
        $categorie->delete();
        return response()->json(null, 204);
    }

    // Additional methods
    public function getEmplacements()
    {
        $emplacements = RefEmplacementActivite::all();
        return response()->json($emplacements);
    }

    public function getTailles()
    {
        $tailles = RefTailleActivite::all();
        return response()->json($tailles);
    }

}