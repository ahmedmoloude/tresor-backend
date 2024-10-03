<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContribuableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can add authorization logic here if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'representant' => 'nullable|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required',
            'dateMiseEnService' => 'required|date',
            'details' => 'nullable|array',  
            'details.*.money' => 'nullable|numeric',  
            'details.*.role' => 'nullable|string|max:255',  
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nom.required' => 'Le nom du contribuable est requis.',
            'adresse.required' => 'L\'adresse est requise.',
            'telephone.required' => 'Le numéro de téléphone est requis.',
    
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant ne peut pas être négatif.',
            'date_mas.required' => 'La date de mise en service est requise.',
            'date_mas.date' => 'La date de mise en service doit être une date valide.',
                // 'activite_id.required' => 'L\'activité est requise.',
            // 'activite_id.exists' => 'L\'activité sélectionnée n\'existe pas.',
            // 'ref_emplacement_activite_id.required' => 'L\'emplacement de l\'activité est requis.',
            // 'ref_emplacement_activite_id.exists' => 'L\'emplacement de l\'activité sélectionné n\'existe pas.',
            // 'ref_taille_activite_id.required' => 'La taille de l\'activité est requise.',
            // 'ref_taille_activite_id.exists' => 'La taille de l\'activité sélectionnée n\'existe pas.',
        ];
    }
}