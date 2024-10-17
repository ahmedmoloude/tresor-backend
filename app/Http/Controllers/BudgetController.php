<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BudgetController extends Controller
{
    /**
     * Display a listing of the budgets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budgets = Budget::with(['commune', 'nomenclature', 'ref_type_budget'])->get();
        return response()->json($budgets);
    }

    /**
     * Store a newly created budget in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'annee' => 'required|exists:annees,annee',
             'libelle' => 'required|string|max:255',
         ]);
 
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         $budget = Budget::create($request->all());
 
         return response()->json($budget, 201);
     }
 
     private function current_year()
     {
         return Annee::where('etat', 1)->first()->annee ?? null;
     }
 
     public function getYears()
     {
         $years = Annee::pluck('annee');
         $currentYear = $this->current_year();
         return response()->json([
             'years' => $years,
             'currentYear' => $currentYear
         ]);
     }
    /**
     * Display the specified budget.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budget = Budget::with(['commune', 'nomenclature', 'ref_type_budget'])->findOrFail($id);
        return response()->json($budget);
    }

    /**
     * Update the specified budget in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'commune_id' => 'exists:communes,id',
            'nomenclature_id' => 'exists:nomenclatures,id',
            'annee' => 'digits:4',
            'libelle' => 'string|max:255',
            'libelle_ar' => 'string|max:255',
            'ref_type_budget_id' => 'exists:ref_type_budgets,id',
            'ordre_complementaire' => 'integer',
            'ref_etat_budget_id' => 'exists:ref_etat_budgets,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $budget->update($request->all());

        return response()->json($budget);
    }

    /**
     * Remove the specified budget from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return response()->json(null, 204);
    }


      /**
     * Upload expense document for a budget.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  


     public function uploadExpenseDocument(Request $request, $id)
     {
         $budget = Budget::findOrFail($id);
 
         $validator = Validator::make($request->all(), [
             'expense_document' => 'required|file|mimes:pdf|max:10240',
         ]);
 
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         if ($request->hasFile('expense_document')) {
             // Delete existing document if it exists
             if ($budget->expense_document_path) {
                 Storage::disk('public')->delete($budget->expense_document_path);
             }
 
             $file = $request->file('expense_document');
             $filename = time() . '_' . $file->getClientOriginalName();
             $path = $file->storeAs('expense_document/' . $id, $filename, 'public');
             $budget->expense_document_path = $path;
             $budget->save();
 
             return response()->json(['message' => 'Document des dépenses téléchargé avec succès', 'path' => $path]);
         }
 
         return response()->json(['error' => 'Aucun fichier téléchargé'], 400);
     }
 
     public function uploadIncomeDocument(Request $request, $id)
     {
         $budget = Budget::findOrFail($id);
 
         $validator = Validator::make($request->all(), [
             'income_document' => 'required|file|mimes:pdf|max:10240',
         ]);
 
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         if ($request->hasFile('income_document')) {
             // Delete existing document if it exists
             if ($budget->income_document_path) {
                 Storage::disk('public')->delete($budget->income_document_path);
             }
 
             $file = $request->file('income_document');
             $filename = time() . '_' . $file->getClientOriginalName();
             $path = $file->storeAs('income_document/' . $id, $filename, 'public');
             $budget->income_document_path = $path;
             $budget->save();
 
             return response()->json(['message' => 'Document des recettes téléchargé avec succès', 'path' => $path]);
         }
 
         return response()->json(['error' => 'Aucun fichier téléchargé'], 400);
     }
}