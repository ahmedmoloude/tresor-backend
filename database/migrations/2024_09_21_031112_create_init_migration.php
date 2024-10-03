<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


     public function up()
     {
         // 1. Create tables with no foreign key dependencies first
 
         Schema::create('ref_applications', function (Blueprint $table) {
             $table->id();
             $table->string('libelle')->nullable();
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_banques', function (Blueprint $table) {
             $table->id();
             $table->string('libelle')->nullable();
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_categorie_activites', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->float('montant')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_categorie_nomenclatures', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_emplacement_activites', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_etats_avancements', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_etats_infrastructures', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('odre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_etat_budgets', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_fonctions', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_genres', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_niveau_etudes', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->integer('ordre')->default(0);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_niveau_importances', function (Blueprint $table) {
             $table->id();
             $table->string('libelle')->nullable();
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_sig_type_geometrys', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_situation_familliales', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_taille_activites', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_types_contrats', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_types_documents', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_types_equipements', function (Blueprint $table) {
             $table->id();
             $table->string('libelle')->nullable();
             $table->string('libelle_ar')->nullable();
             $table->integer('type_affichage');
             $table->text('image')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_types_infrastructures', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_types_maintenances', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_archives', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_budgets', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_depenses', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_emplacements', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_nomenclatures', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_payements', function (Blueprint $table) {
             $table->id();
             $table->string('libelle')->nullable();
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('ref_type_recettes', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('wilayas', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->text('adresse_gps')->nullable();
             $table->text('contour_gps')->nullable();
             $table->decimal('nbr_habitants', 15, 2)->default(0);
             $table->string('code');
             $table->text('path_carte')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('annees', function (Blueprint $table) {
             $table->id();
             $table->string('annee');
             $table->integer('etat')->default(0);
             $table->string('description')->nullable();
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('mois', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('specialites', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->integer('ordre')->default(0);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('sys_types_users', function (Blueprint $table) {
             $table->id();
             $table->string('libelle')->nullable();
             $table->string('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         // 2. Create tables with foreign key dependencies
 
         Schema::create('moughataas', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->text('adresse_gps')->nullable();
             $table->text('Contour_pgs')->nullable();
             $table->integer('nbr_habitants')->default(0);
             $table->unsignedBigInteger('wilaya_id');
             $table->string('code');
             $table->text('path_carte')->nullable();
             $table->timestamps();
             $table->softDeletes();
 
             $table->foreign('wilaya_id')->references('id')->on('wilayas')->onDelete('cascade');
         });
 
         Schema::create('communes', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar')->nullable();
             $table->text('adresse_GPS')->nullable();
             $table->text('contour_gps')->nullable();
             $table->integer('nbr_habitans')->default(0);
             $table->integer('classe_population')->default(0);
             $table->unsignedBigInteger('moughataa_id');
             $table->string('code');
             $table->string('nom_Maire');
             $table->string('nom_SG');
             $table->integer('surface')->default(0);
             $table->integer('nbr_villages_localites')->nullable();
             $table->string('decret_de_creation')->nullable();
             $table->integer('nbr_conseillers_municipaux')->nullable();
             $table->integer('nbr_employes_municipaux_permanents')->nullable();
             $table->integer('nbr_employes_municipaux_temporaires')->nullable();
             $table->boolean('secretaire_generale')->nullable();
             $table->boolean('pnidelle')->nullable();
             $table->boolean('organisations_internationale')->nullable();
             $table->boolean('recettes_impots')->nullable();
             $table->boolean('eclairage_public')->nullable();
             $table->text('path_carte')->nullable();
             $table->timestamps();
             $table->softDeletes();
 
             $table->foreign('moughataa_id')->references('id')->on('moughataas')->onDelete('cascade');
         });
 
         Schema::create('services', function (Blueprint $table) {
             $table->id();
             $table->text('libelle');
             $table->text('libelle_ar')->nullable();
             $table->integer('ordre')->default(1);
             $table->unsignedBigInteger('commune_id');
             $table->timestamps();
             $table->softDeletes();
 
             $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
         });
 
         Schema::create('localites', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->string('coordonnees_gps');
             $table->unsignedBigInteger('commune_id');
             $table->integer('surface')->nullable();
             $table->integer('population')->nullable();
             $table->timestamps();
             $table->softDeletes();
 
             $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
         });
 
         Schema::create('secteurs', function (Blueprint $table) {
             $table->id();
             $table->integer('parent')->default(0);
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->integer('ordre')->default(1);
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('users', function (Blueprint $table) {
             $table->id();
             $table->string('name')->nullable();
             $table->string('username')->nullable();
             $table->string('email');
             $table->string('password');

             $table->unsignedBigInteger('sys_types_user_id')->nullable();
             $table->boolean('etat')->default(true);
             $table->string('phone')->nullable();
             $table->string('code')->nullable();
             $table->boolean('confirm')->default(false);
             $table->timestamps();
             $table->softDeletes();

             $table->timestamp('email_verified_at')->nullable();

             $table->rememberToken();


 
             $table->foreign('sys_types_user_id')->references('id')->on('sys_types_users')->onDelete('set null');
         });



        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
 
         Schema::create('nomenclatures', function (Blueprint $table) {
             $table->id();
             $table->string('libelle');
             $table->string('libelle_ar');
             $table->timestamps();
             $table->softDeletes();
         });
 
         Schema::create('nomenclature_elements', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('ref_categorie_nomenclature_id');
             $table->unsignedBigInteger('ref_type_nomenclature_id');
             $table->string('code');

             $table->string('libelle');
            $table->string('libelle_ar');
            $table->integer('niveau');
            $table->integer('parent');
            $table->integer('ordre');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_categorie_nomenclature_id')->references('id')->on('ref_categorie_nomenclatures')->onDelete('cascade');
            $table->foreign('ref_type_nomenclature_id')->references('id')->on('ref_type_nomenclatures')->onDelete('cascade');
        });

        Schema::create('rel_nomenclature_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id');
            $table->unsignedBigInteger('nomenclature_element_id');
            $table->integer('ordre');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nomenclature_id')->references('id')->on('nomenclatures')->onDelete('cascade');
            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('cascade');
        });

        Schema::create('compte_impitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_element_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('set null');
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commune_id');
            $table->unsignedBigInteger('nomenclature_id');
            $table->string('annee')->nullable();
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->unsignedBigInteger('ref_type_budget_id');
            $table->integer('ordre_complementaire');
            $table->unsignedBigInteger('ref_etat_budget_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
            $table->foreign('nomenclature_id')->references('id')->on('nomenclatures')->onDelete('cascade');
            $table->foreign('ref_type_budget_id')->references('id')->on('ref_type_budgets')->onDelete('cascade');
            $table->foreign('ref_etat_budget_id')->references('id')->on('ref_etat_budgets')->onDelete('cascade');
        });

        Schema::create('budget_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id');
            $table->unsignedBigInteger('nomenclature_element_id');
            $table->decimal('montant', 15, 2);
            $table->integer('montant_realise')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('cascade');
        });

        Schema::create('activites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_categorie_activite_id');
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_categorie_activite_id')->references('id')->on('ref_categorie_activites')->onDelete('cascade');
        });

        Schema::create('contribuables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activite_id');
            $table->unsignedBigInteger('ref_emplacement_activite_id');
            $table->unsignedBigInteger('ref_taille_activite_id');
            $table->string('libelle')->nullable();
            $table->string('article')->nullable();
            $table->string('periode')->nullable();
            $table->string('nif')->nullable();
            $table->string('libelle_ar')->nullable();
            $table->string('representant')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('montant')->default('0');
            $table->date('date_mas')->nullable();
            $table->integer('etat')->default(0);
            $table->unsignedBigInteger('nomenclature_element_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('activite_id')->references('id')->on('activites')->onDelete('cascade');
            $table->foreign('ref_emplacement_activite_id')->references('id')->on('ref_emplacement_activites')->onDelete('cascade');
            $table->foreign('ref_taille_activite_id')->references('id')->on('ref_taille_activites')->onDelete('cascade');
            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('set null');
        });

        Schema::create('contribuables_annees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contribuable_id');
            $table->string('annee');
            $table->integer('spontane')->nullable();
            $table->string('etat')->nullable();
            $table->string('montant')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
        });

        Schema::create('protocoles', function (Blueprint $table) {
            $table->id();
            $table->text('libelle')->nullable();
            $table->unsignedBigInteger('contribuable_id')->nullable();
            $table->decimal('montant', 15, 2)->nullable();
            $table->text('remarque')->nullable();
            $table->date('dateEch')->nullable();
            $table->string('etat')->nullable();
            $table->decimal('montant_arriere', 15, 2);
            $table->string('montantdegv')->nullable();
            $table->unsignedBigInteger('annee_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('set null');
            $table->foreign('annee_id')->references('id')->on('annees')->onDelete('set null');
        });

        Schema::create('echeances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protocol_id')->nullable();
            $table->date('dateEch')->nullable();
            $table->string('montant')->nullable();
            $table->string('montantdegv')->nullable();
            $table->string('etat')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('protocol_id')->references('id')->on('protocoles')->onDelete('set null');
        });

        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->string('annee');
            $table->unsignedBigInteger('protocol_id');
            $table->unsignedBigInteger('contribuable_id');
            $table->string('etat')->nullable();
            $table->decimal('montant', 15, 2);
            $table->date('date');
            $table->decimal('montant_arriere', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('protocol_id')->references('id')->on('protocoles')->onDelete('cascade');
            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
        });

        Schema::create('details_payements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payement_id');
            $table->decimal('montant', 15, 2);
            $table->string('description')->nullable();
            $table->string('mode_payement')->nullable();
            $table->string('banque')->nullable();
            $table->string('compte')->nullable();
            $table->string('num_cheque')->nullable();
            $table->string('nom_app')->nullable();
            $table->string('quitance')->nullable();
            $table->string('titre')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('payement_id')->references('id')->on('payements')->onDelete('cascade');
        });

        Schema::create('roles_annees', function (Blueprint $table) {
            $table->id();
            $table->string('annee');
            $table->string('libelle')->nullable();
            $table->integer('etat')->nullable();
            $table->unsignedBigInteger('nomenclature_element_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('set null');
        });

        Schema::create('roles_contribuables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contribuable_id');
            $table->unsignedBigInteger('role_id');
            $table->string('annee');
            $table->string('montantDroit')->nullable();
            $table->string('penelite')->nullable();
            $table->string('montant')->nullable();
            $table->string('montant_paye')->nullable();
            $table->string('periode')->nullable();
            $table->string('date_fin')->nullable();
            $table->string('adresses')->nullable();
            $table->string('emeregement')->nullable();
            $table->string('article')->nullable();
            $table->string('anneerel')->nullable();
            $table->unsignedBigInteger('protocole_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles_annees')->onDelete('cascade');
            $table->foreign('protocole_id')->references('id')->on('protocoles')->onDelete('set null');
        });



        Schema::create('payementmens', function (Blueprint $table) {
            $table->id();

            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->string('annee');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('contribuable_id');
            $table->integer('etat');
            $table->decimal('montant', 15, 2);
            $table->date('date');
            $table->decimal('montant_arriere', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id')->references('id')->on('roles_annees')->onDelete('set null');
            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
        });

        Schema::create('details_payementmens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payement_id');
            $table->decimal('montant', 15, 2);
            $table->string('description')->nullable();
            $table->string('mode_payement')->nullable();
            $table->string('banque')->nullable();
            $table->string('compte')->nullable();
            $table->string('num_cheque')->nullable();
            $table->string('nom_app')->nullable();
            $table->string('quitance')->nullable();
            $table->string('titre')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('payement_id')->references('id')->on('payementmens')->onDelete('cascade');
        });

        Schema::create('garde_roles_contribuables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contribuable_id');
            $table->unsignedBigInteger('role_id');
            $table->string('annee');
            $table->string('montant')->nullable();
            $table->string('periode')->nullable();
            $table->string('emeregement')->nullable();
            $table->string('article')->nullable();
            $table->string('anneerel')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles_annees')->onDelete('cascade');
        });

        Schema::create('degrevement_contribuables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contribuable_id');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->unsignedBigInteger('protocol_id')->nullable();
            $table->string('annee');
            $table->string('montant')->nullable();
            $table->string('periode')->nullable();
            $table->string('emeregement')->nullable();
            $table->string('article')->nullable();
            $table->string('decision')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
            $table->foreign('protocol_id')->references('id')->on('protocoles')->onDelete('set null');
        });

        Schema::create('mois_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mois_id');
            $table->string('annee');
            $table->unsignedBigInteger('contribuable_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mois_id')->references('id')->on('mois')->onDelete('cascade');
            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
        });

        Schema::create('programmejours', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->date('date')->nullable();
            $table->integer('etat')->nullable();
            $table->unsignedBigInteger('annee_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('annee_id')->references('id')->on('annees')->onDelete('set null');
        });

        Schema::create('programmejourconts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programmejour_id')->nullable();
            $table->unsignedBigInteger('contribuable_id')->nullable();
            $table->integer('etat')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('programmejour_id')->references('id')->on('programmejours')->onDelete('set null');
            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('set null');
        });

        Schema::create('suspenssion_contribuables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contribuable_id');
            $table->integer('mois_debut');
            $table->integer('mois_fin');
            $table->string('annee');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuable_id')->references('id')->on('contribuables')->onDelete('cascade');
        });

        Schema::create('forchette_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_emplacement_activite_id');
            $table->unsignedBigInteger('ref_taille_activite_id');
            $table->unsignedBigInteger('ref_categorie_activite_id');
            $table->decimal('montant', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_emplacement_activite_id')->references('id')->on('ref_emplacement_activites')->onDelete('cascade');
            $table->foreign('ref_taille_activite_id')->references('id')->on('ref_taille_activites')->onDelete('cascade');
            $table->foreign('ref_categorie_activite_id')->references('id')->on('ref_categorie_activites')->onDelete('cascade');
        });

        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('nom_famille')->nullable();
            $table->string('nom_ar')->nullable();
            $table->string('prenom_ar')->nullable();
            $table->string('nom_famille_ar')->nullable();
            $table->string('nni')->nullable();
            $table->unsignedBigInteger('ref_genre_id');
            $table->date('date_naissance')->nullable();
            $table->unsignedBigInteger('lieu_naissance');
            $table->unsignedBigInteger('ref_situation_familliale_id');
            $table->text('photo')->nullable();
            $table->unsignedBigInteger('ref_niveau_etude_id')->nullable();
            $table->unsignedBigInteger('specialite_id')->nullable();
            $table->date('date_embauche')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('ref_fonction_id')->nullable();
            $table->string('taches')->nullable();
            $table->unsignedBigInteger('ref_types_contrat_id')->nullable();
            $table->text('titre')->nullable();
            $table->string('salaire_mensuel')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('adress')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('commentaires')->nullable();
            // $table->unsignedBigInteger('ref_appreciations_hierarchie_id')->nullable();
            $table->string('prenom_personne')->nullable();
            $table->string('nom_personne')->nullable();
            $table->string('tel_personne')->nullable();
            $table->string('email_personne')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_genre_id')->references('id')->on('ref_genres')->onDelete('cascade');
            $table->foreign('lieu_naissance')->references('id')->on('communes')->onDelete('cascade');
            $table->foreign('ref_situation_familliale_id')->references('id')->on('ref_situation_familliales')->onDelete('cascade');
            $table->foreign('ref_niveau_etude_id')->references('id')->on('ref_niveau_etudes')->onDelete('set null');
            $table->foreign('specialite_id')->references('id')->on('specialites')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            $table->foreign('ref_fonction_id')->references('id')->on('ref_fonctions')->onDelete('set null');
            $table->foreign('ref_types_contrat_id')->references('id')->on('ref_types_contrats')->onDelete('set null');
            // $table->foreign('ref_appreciations_hierarchie_id')->references('id')->on('ref_appreciations_hierarchies')->onDelete('set null');
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->integer('annee_deb');
            $table->integer('mois_deb')->nullable();
            $table->integer('jour_deb')->nullable();
            $table->boolean('encours')->default(false);
            $table->integer('annee_fin')->nullable();
            $table->integer('mois_fin')->nullable();
            $table->integer('jour_fin')->nullable();
            $table->text('mission_principal')->nullable();
            $table->unsignedBigInteger('employe_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('employe_id')->references('id')->on('employes')->onDelete('cascade');
        });

        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->string('libelle_ar')->nullable();
            $table->string('code')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->unsignedBigInteger('localite_id');
            $table->string('deliberatin_patrimoine_communal')->nullable();
            $table->boolean('patrimoine_public')->nullable();
            $table->string('num_deliberation')->nullable();
            $table->date('date_deliberation')->nullable();
            $table->string('image')->nullable();
            $table->boolean('Eau')->default(false);
            $table->boolean('electricite')->default(false);
            $table->boolean('service_hygiene_assainissement')->default(false);
            $table->boolean('accessibilite')->default(false);
            $table->text('situation_environnementale')->nullable();
            $table->unsignedBigInteger('ref_types_equipement_id');
            $table->unsignedBigInteger('secteur_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('ancien_eq')->nullable();
            $table->decimal('valeur', 15, 2)->default(0);
            $table->integer('active')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('localite_id')->references('id')->on('localites')->onDelete('cascade');
            $table->foreign('ref_types_equipement_id')->references('id')->on('ref_types_equipements')->onDelete('cascade');
            $table->foreign('secteur_id')->references('id')->on('secteurs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('ref_elements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->integer('type')->default(1);
            $table->unsignedBigInteger('ref_types_equipement_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_types_equipement_id')->references('id')->on('ref_types_equipements')->onDelete('cascade');
        });

        Schema::create('equipements_ref_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipement_id');
            $table->unsignedBigInteger('ref_element_id');
            $table->text('valeur');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade');
            $table->foreign('ref_element_id')->references('id')->on('ref_elements')->onDelete('cascade');
        });

        Schema::create('coordonnees_equipements_geo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipement_id');
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade');
        });

        Schema::create('image_equipements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipement_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade');
        });

        Schema::create('infrastructures', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->date('date_construction')->nullable();
            $table->string('valeur')->nullable();
            $table->unsignedBigInteger('ref_types_infrastructure_id');
            $table->unsignedBigInteger('equipement_id');
            $table->unsignedBigInteger('ref_etats_infrastructure_id');
            $table->date('date_echange')->nullable();
            $table->date('date_attribution')->nullable();
            $table->integer('ancien_parent')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_types_infrastructure_id')->references('id')->on('ref_types_infrastructures')->onDelete('cascade');
            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade');
            $table->foreign('ref_etats_infrastructure_id')->references('id')->on('ref_etats_infrastructures')->onDelete('cascade');
        });

        Schema::create('plans_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->unsignedBigInteger('equipement_id');
            $table->date('date_plan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade');
        });

        Schema::create('items_plans_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plans_maintenance_id');
            $table->text('commentaires')->nullable();
            $table->unsignedBigInteger('infrastructure_id');
            $table->unsignedBigInteger('ref_types_maintenance_id');
            $table->date('date_de')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plans_maintenance_id')->references('id')->on('plans_maintenances')->onDelete('cascade');
            $table->foreign('infrastructure_id')->references('id')->on('infrastructures')->onDelete('cascade');
            $table->foreign('ref_types_maintenance_id')->references('id')->on('ref_types_maintenances')->onDelete('cascade');
        });

        Schema::create('suivis_items_plans_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('items_plans_maintenance_id');
            $table->date('date_suivi')->nullable();
            $table->unsignedBigInteger('ref_etats_avancement_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('items_plans_maintenance_id')->references('id')->on('items_plans_maintenances')->onDelete('cascade');
            $table->foreign('ref_etats_avancement_id')->references('id')->on('ref_etats_avancements')->onDelete('cascade');
        });

        Schema::create('suivi_historiques_equipements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipement_id');
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->string('num_deliberation')->nullable();
            $table->date('date_deliberation')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade');
        });

        Schema::create('emplacements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_type_emplacement_id')->nullable();
            $table->unsignedBigInteger('localite_id')->nullable();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('ordre')->nullable();
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_type_emplacement_id')->references('id')->on('ref_type_emplacements')->onDelete('set null');
            $table->foreign('localite_id')->references('id')->on('localites')->onDelete('set null');
        });

        Schema::create('image_emplacements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emplacement_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('emplacement_id')->references('id')->on('emplacements')->onDelete('cascade');
        });

        Schema::create('ar_emplacements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->integer('type_archive');
            $table->string('code');
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ar_origines', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ar_qualites', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->unsignedBigInteger('ref_type_archive_id');
            $table->integer('etat')->default(1);
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('ar_qualite_id')->nullable();
            $table->unsignedBigInteger('ar_emplacement_id')->nullable();
            $table->integer('num_dossier')->nullable();
            $table->integer('num_archive')->nullable();
            $table->string('description')->nullable();
            $table->string('mots_cles')->nullable();
            $table->date('date_archivage');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_type_archive_id')->references('id')->on('ref_type_archives')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('ar_qualite_id')->references('id')->on('ar_qualites')->onDelete('set null');
            $table->foreign('ar_emplacement_id')->references('id')->on('ar_emplacements')->onDelete('set null');
        });

        Schema::create('courriers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('titre');
            $table->string('description')->nullable();
            $table->integer('type');
            $table->unsignedBigInteger('ar_origine_id');
            $table->date('date_transaction');
            $table->unsignedBigInteger('service_id');
            $table->integer('ref_niveau_importances');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ar_origine_id')->references('id')->on('ar_origines')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });

        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('annee');
            $table->integer('ref_type_depenses');
            $table->unsignedBigInteger('nomenclature_element_id');
            $table->date('date');
            $table->decimal('montant', 15, 2)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('ged');
            $table->text('beneficiaire')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('recettes', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('annee');
            $table->unsignedBigInteger('ref_type_recette_id');
            $table->unsignedBigInteger('nomenclature_element_id');
            $table->date('date');
            $table->decimal('montant', 15, 2)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('ged');
            $table->text('origine')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_type_recette_id')->references('id')->on('ref_type_recettes')->onDelete('cascade');
            $table->foreign('nomenclature_element_id')->references('id')->on('nomenclature_elements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('ged', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->text('emplacement');
            $table->unsignedBigInteger('objet_id');
            $table->integer('type')->default(1);
            $table->string('extension');
            $table->unsignedBigInteger('ref_types_document_id');
            $table->text('commentaire')->nullable();
            $table->integer('taille')->nullable();
            $table->integer('type_ged')->nullable();
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_types_document_id')->references('id')->on('ref_types_documents')->onDelete('cascade');
        });

        Schema::create('modeles_actes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('titre_ar');
            $table->string('libelle');
            $table->string('libelle_ar');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modeles_actes_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modeles_acte_id');
            $table->integer('nature_content');
            $table->text('content_value');
            $table->text('content_value_ar');
            $table->integer('type_content')->nullable();
            $table->integer('ordre');
            $table->string('postion');
            $table->string('alignement')->nullable();
            $table->string('nom_item')->nullable();
            $table->integer('parent')->default(0);
            $table->integer('ligne')->nullable();
            $table->string('texte_secondaire')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('modeles_acte_id')->references('id')->on('modeles_actes')->onDelete('cascade');
        });

        Schema::create('actes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modeles_acte_id');
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->date('date');
            $table->integer('num')->nullable();
            $table->integer('etat')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('modeles_acte_id')->references('id')->on('modeles_actes')->onDelete('cascade');
        });

        Schema::create('actes_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acte_id');
            $table->unsignedBigInteger('modeles_actes_item_id');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('acte_id')->references('id')->on('actes')->onDelete('cascade');
            $table->foreign('modeles_actes_item_id')->references('id')->on('modeles_actes_items')->onDelete('cascade');
        });

        Schema::create('ref_choix_itemes_actes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->integer('ordre')->default(1);
            $table->unsignedBigInteger('modeles_actes_item_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('modeles_actes_item_id')->references('id')->on('modeles_actes_items')->onDelete('cascade');
        });

        Schema::create('ref_choix_elements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->integer('ordre')->default(1);
            $table->unsignedBigInteger('ref_element_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_element_id')->references('id')->on('ref_elements')->onDelete('cascade');
        });

        Schema::create('entete_communes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commune_id');
            $table->string('titre1');
            $table->string('titre1_ar');
            $table->string('titre2');
            $table->string('titre2_ar');
            $table->string('titre3');
            $table->string('titre3_ar');
            $table->string('logo');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
        });

        Schema::create('sig_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('crs_name')->nullable();
            $table->integer('niveau');
            $table->unsignedBigInteger('ref_types_objets_geo_id')->nullable();
            $table->unsignedBigInteger('ref_sig_type_geometry_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ref_sig_type_geometry_id')->references('id')->on('ref_sig_type_geometrys')->onDelete('set null');
        });

        Schema::create('sig_objects_layouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sig_layout_id');
            $table->unsignedBigInteger('object_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sig_layout_id')->references('id')->on('sig_layouts')->onDelete('cascade');
        });

        Schema::create('sig_coordinates_objects', function (Blueprint $table) {
            $table->id();
            $table->text('loguitude');
            $table->text('latitude');
            $table->unsignedBigInteger('sig_objects_layout_id');
            $table->integer('grouping')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sig_objects_layout_id')->references('id')->on('sig_objects_layouts')->onDelete('cascade');
        });

        Schema::create('sys_groupes_traitements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->integer('ordre')->default(1);
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sys_droits', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->integer('type_acces');
            $table->unsignedBigInteger('sys_groupes_traitement_id');
            $table->integer('ordre')->default(1);
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sys_groupes_traitement_id')->references('id')->on('sys_groupes_traitements')->onDelete('cascade');
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('libelle_ar')->nullable();
            $table->boolean('is_externe')->default(false);
            $table->string('lien');
            $table->string('icone')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color');
            $table->unsignedBigInteger('sys_groupes_traitement_id');
            
            $table->foreign('sys_groupes_traitement_id')->references('id')->on('sys_groupes_traitements')->onDelete('cascade');
        });

        Schema::create('sys_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sys_profiles_sys_droits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sys_profile_id');
            $table->unsignedBigInteger('sys_droit_id');
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sys_profile_id')->references('id')->on('sys_profiles')->onDelete('cascade');
            $table->foreign('sys_droit_id')->references('id')->on('sys_droits')->onDelete('cascade');
        });

        Schema::create('sys_profiles_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sys_profile_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sys_profile_id')->references('id')->on('sys_profiles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('set null');
        });

        // Create the migrations table (if not already created by Laravel)
        if (!Schema::hasTable('migrations')) {
            Schema::create('migrations', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->integer('batch');
            });
        }
    }



    public function down()
    {
        // Drop all tables in reverse order
        Schema::dropIfExists('sys_profiles_users');
        Schema::dropIfExists('sys_profiles_sys_droits');
        Schema::dropIfExists('sys_profiles');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('sys_droits');
        Schema::dropIfExists('sys_groupes_traitements');
        Schema::dropIfExists('sig_coordinates_objects');
        Schema::dropIfExists('sig_objects_layouts');
        Schema::dropIfExists('sig_layouts');
        Schema::dropIfExists('entete_communes');
        Schema::dropIfExists('ref_choix_elements');
        Schema::dropIfExists('ref_choix_itemes_actes');
        Schema::dropIfExists('actes_values');
        Schema::dropIfExists('actes');
        Schema::dropIfExists('modeles_actes_items');
        Schema::dropIfExists('modeles_actes');
        Schema::dropIfExists('ged');
        Schema::dropIfExists('recettes');
        Schema::dropIfExists('depenses');
        Schema::dropIfExists('courriers');
        Schema::dropIfExists('archives');
        Schema::dropIfExists('ar_qualites');
        Schema::dropIfExists('ar_origines');
        Schema::dropIfExists('ar_emplacements');
        Schema::dropIfExists('image_emplacements');
        Schema::dropIfExists('emplacements');
        Schema::dropIfExists('suivi_historiques_equipements');
        Schema::dropIfExists('suivis_items_plans_maintenances');
        Schema::dropIfExists('items_plans_maintenances');
        Schema::dropIfExists('plans_maintenances');
        Schema::dropIfExists('infrastructures');
        Schema::dropIfExists('image_equipements');
        Schema::dropIfExists('coordonnees_equipements_geo');
        Schema::dropIfExists('equipements_ref_elements');
        Schema::dropIfExists('ref_elements');
        Schema::dropIfExists('equipements');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('employes');
        Schema::dropIfExists('forchette_taxes');
        Schema::dropIfExists('suspenssion_contribuables');
        Schema::dropIfExists('programmejourconts');
        Schema::dropIfExists('programmejours');
        Schema::dropIfExists('mois_services');
        Schema::dropIfExists('degrevement_contribuables');
        Schema::dropIfExists('garde_roles_contribuables');
        Schema::dropIfExists('details_payementmens');
        Schema::dropIfExists('payementmens');
        Schema::dropIfExists('roles_contribuables');
        Schema::dropIfExists('roles_annees');
        Schema::dropIfExists('details_payements');
        Schema::dropIfExists('payements');
        Schema::dropIfExists('echeances');
        Schema::dropIfExists('protocoles');
        Schema::dropIfExists('contribuables_annees');
        Schema::dropIfExists('contribuables');
        Schema::dropIfExists('activites');
        Schema::dropIfExists('budget_details');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('compte_impitations');
        Schema::dropIfExists('rel_nomenclature_elements');
        Schema::dropIfExists('nomenclature_elements');
        Schema::dropIfExists('nomenclatures');
        Schema::dropIfExists('users');
        Schema::dropIfExists('secteurs');
        Schema::dropIfExists('localites');
        Schema::dropIfExists('services');
        Schema::dropIfExists('communes');
        Schema::dropIfExists('moughataas');
        Schema::dropIfExists('wilayas');
        Schema::dropIfExists('ref_type_recettes');
        Schema::dropIfExists('ref_type_payements');
        Schema::dropIfExists('ref_type_nomenclatures');
        Schema::dropIfExists('ref_type_emplacements');
        Schema::dropIfExists('ref_type_depenses');
        Schema::dropIfExists('ref_type_budgets');
        Schema::dropIfExists('ref_type_archives');
        Schema::dropIfExists('ref_types_maintenances');
        Schema::dropIfExists('ref_types_infrastructures');
        Schema::dropIfExists('ref_types_equipements');
        Schema::dropIfExists('ref_types_documents');
        Schema::dropIfExists('ref_types_contrats');
        Schema::dropIfExists('ref_taille_activites');
        Schema::dropIfExists('ref_situation_familliales');
        Schema::dropIfExists('ref_sig_type_geometrys');
        Schema::dropIfExists('ref_niveau_importances');
        Schema::dropIfExists('ref_niveau_etudes');
        Schema::dropIfExists('ref_genres');
        Schema::dropIfExists('ref_fonctions');
        Schema::dropIfExists('ref_etat_budgets');
        Schema::dropIfExists('ref_etats_infrastructures');
        Schema::dropIfExists('ref_etats_avancements');
        Schema::dropIfExists('ref_emplacement_activites');
        Schema::dropIfExists('ref_categorie_nomenclatures');
        Schema::dropIfExists('ref_categorie_activites');
        Schema::dropIfExists('ref_banques');
        Schema::dropIfExists('ref_applications');
        Schema::dropIfExists('specialites');
        Schema::dropIfExists('mois');
        Schema::dropIfExists('annees');
        Schema::dropIfExists('sys_types_users');
        Schema::dropIfExists('migrations');
    }


};
