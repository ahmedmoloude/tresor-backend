<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('commune_id')->nullable()->change();
            $table->unsignedBigInteger('nomenclature_id')->nullable()->change();
            $table->string('libelle')->nullable()->change();
            $table->string('libelle_ar')->nullable()->change();
            $table->unsignedBigInteger('ref_type_budget_id')->nullable()->change();
            $table->integer('ordre_complementaire')->nullable()->change();
            $table->unsignedBigInteger('ref_etat_budget_id')->nullable()->change();

            $table->string('expense_document_path')->nullable();
            $table->string('income_document_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
