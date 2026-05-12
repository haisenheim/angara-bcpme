<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Charge la taxonomie de la grille « TABLEAU GRILLE TRAITEMENT ENGAGEMENTS ».
 *
 * Quatre sections (EMPRUNTS BANCAIRES, INVESTISSEMENTS DIRECTS,
 * COMPENSATION DES BIENS ET SERVICES, GARANTIES DONNEES), leurs rubriques /
 * natures intermédiaires, et la liste des produits saisissables (is_leaf=1).
 */
return new class extends Migration
{
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('engagement_categories')) {
            return;
        }

        $now = now();
        $rows = [];
        $idMap = [];
        $nextId = 1;

        $append = function (
            string $code,
            string $libelle,
            string $type,
            ?string $parentCode,
            bool $isLeaf,
            int $sortOrder,
            ?string $description = null,
        ) use (&$rows, &$idMap, &$nextId, $now): void {
            $idMap[$code] = $nextId;
            $rows[] = [
                'id' => $nextId,
                'code' => $code,
                'libelle' => $libelle,
                'parent_id' => $parentCode ? ($idMap[$parentCode] ?? null) : null,
                'type' => $type,
                'is_leaf' => $isLeaf ? 1 : 0,
                'sort_order' => $sortOrder,
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $nextId++;
        };

        // 1. EMPRUNTS BANCAIRES
        $append('EB', 'Emprunts bancaires', 'section', null, false, 100);

        $append('EB.CC', 'Crédits courants en FCFA', 'rubrique', 'EB', false, 110);
        $append('EB.CC.MOB', 'Mobilisation de créances', 'nature', 'EB.CC', false, 111);
        $append('EB.CC.MOB.ESCOMPTE', 'Escompte effets', 'produit', 'EB.CC.MOB', true, 112);
        $append('EB.CC.MOB.AFFACTURAGE', 'Affacturage', 'produit', 'EB.CC.MOB', true, 113);
        $append('EB.CC.MOB.AUTRES', 'Autres mobilisations', 'produit', 'EB.CC.MOB', true, 114);

        $append('EB.CC.FAC', 'Facilités de caisse', 'nature', 'EB.CC', false, 115);
        $append('EB.CC.FAC.DECOUVERT', 'Découvert', 'produit', 'EB.CC.FAC', true, 116);
        $append('EB.CC.FAC.SPOT', 'Crédit spot', 'produit', 'EB.CC.FAC', true, 117);
        $append('EB.CC.FAC.AMORT', 'Crédit amortissable', 'produit', 'EB.CC.FAC', true, 118);
        $append('EB.CC.FAC.AUTRES', 'Autres facilités', 'produit', 'EB.CC.FAC', true, 119);

        $append('EB.CMLT', 'Crédits à moyen et long termes en FCFA', 'rubrique', 'EB', false, 120);
        $append('EB.CMLT.MT', 'Emprunt à moyen terme', 'produit', 'EB.CMLT', true, 121);
        $append('EB.CMLT.LT', 'Emprunt à long terme', 'produit', 'EB.CMLT', true, 122);
        $append('EB.CMLT.LEASING', 'Crédit-bail', 'produit', 'EB.CMLT', true, 123);

        $append('EB.SIG', 'Engagements par signature en FCFA', 'rubrique', 'EB', false, 130);
        $append('EB.SIG.DON', "Engagements par signature donnés à l'entreprise", 'nature', 'EB.SIG', false, 131);
        $append('EB.SIG.DON.FIN', 'Engagements de financement', 'produit', 'EB.SIG.DON', true, 132);
        $append('EB.SIG.DON.GAR', 'Engagements de garantie', 'produit', 'EB.SIG.DON', true, 133);
        $append('EB.SIG.DON.TIT', 'Engagements sur titre', 'produit', 'EB.SIG.DON', true, 134);

        $append('EB.SIG.REC', "Engagements par signature reçus de l'entreprise", 'nature', 'EB.SIG', false, 135);
        $append('EB.SIG.REC.FIN', 'Engagements de financement', 'produit', 'EB.SIG.REC', true, 136);
        $append('EB.SIG.REC.GAR', 'Engagements de garantie dont hypothèque', 'produit', 'EB.SIG.REC', true, 137);
        $append('EB.SIG.REC.TIT', 'Engagements sur titre', 'produit', 'EB.SIG.REC', true, 138);

        // 2. INVESTISSEMENTS DIRECTS
        $append('ID', 'Investissements directs', 'section', null, false, 200);
        $append('ID.PARTICIPATION', 'Prise de participation', 'produit', 'ID', true, 201);
        $append('ID.CAPITAL_RISQUE', 'Capital-risque', 'produit', 'ID', true, 202);
        $append('ID.OBLIGATIONS', 'Obligations', 'produit', 'ID', true, 203);
        $append('ID.SUBVENTIONS', "Subventions d'investissements", 'produit', 'ID', true, 204);

        // 3. COMPENSATION DES BIENS ET SERVICES (crédit fournisseur)
        $append('CMP', 'Compensation des biens et services (crédit fournisseur)', 'section', null, false, 300);
        $append('CMP.MARCH_LOCAL', 'Marchandises produites localement', 'produit', 'CMP', true, 301);
        $append('CMP.MARCH_IMPORT', 'Marchandises importées', 'produit', 'CMP', true, 302);
        $append('CMP.PROD_FAB', 'Produits fabriqués (localement)', 'produit', 'CMP', true, 303);
        $append('CMP.SERVICES', 'Services', 'produit', 'CMP', true, 304);

        // 4. GARANTIES DONNEES
        $append('GAR', 'Garanties données', 'section', null, false, 400);
        $append('GAR.MUTUEL', 'Cautionnement mutuel', 'produit', 'GAR', true, 401);
        $append('GAR.HYPOTHEQUE', 'Garantie hypothécaire', 'produit', 'GAR', true, 402);
        $append('GAR.SOUVERAINE', 'Garantie souveraine', 'produit', 'GAR', true, 403);
        $append('GAR.ASSURANCE', 'Garantie assurance', 'produit', 'GAR', true, 404);
        $append('GAR.CONTREGAR', 'Contregarantie bancaire', 'produit', 'GAR', true, 405);

        DB::connection($this->connection)
            ->table('engagement_categories')
            ->upsert(
                $rows,
                ['code'],
                ['libelle', 'parent_id', 'type', 'is_leaf', 'sort_order', 'description', 'updated_at'],
            );
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('engagement_categories')) {
            return;
        }

        DB::connection($this->connection)->table('engagement_categories')->truncate();
    }
};
