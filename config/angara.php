<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rôles métier (table users.role_id = profils.id)
    |--------------------------------------------------------------------------
    | Référentiel BC-PME attendu (profil_modifs.txt).
    | Table profils : users.role_id = profils.id
    |
    | Profil d’instruction des dossiers (id 17) — une seule entrée, libellés métier équivalents :
    | « analyste financier », « analyste financier d’exploitation » (AFE), « analyste » (instruction).
    | Rattachement à une agence optionnel ; l’instruction des dossiers relève toujours de ce profil.
    | L’affectation par le responsable se fait parmi les users avec role_id = ce profil (17 par défaut).
    */
    'role_responsable_exploitation' => (int) env('ANGARA_ROLE_RESP_EXPLOITATION', 6),
    'role_responsable_audit_interne' => (int) env('ANGARA_ROLE_RESP_AUDIT', 7),
    'role_responsable_controle_interne' => (int) env('ANGARA_ROLE_RESP_CONTROLE_INTERNE', 8),
    'role_responsable_engagements' => (int) env('ANGARA_ROLE_RESP_ENGAGEMENTS', 9),
    'role_responsable_juridique' => (int) env('ANGARA_ROLE_RESP_JURIDIQUE', 10),
    'role_responsable_conformite' => (int) env('ANGARA_ROLE_RESP_CONFORMITE', 11),
    'role_responsable_risques' => (int) env('ANGARA_ROLE_RESP_RISQUES', 12),
    'role_responsable_regional' => (int) env('ANGARA_ROLE_RESP_REGIONAL', 14),
    'role_chef_agence' => (int) env('ANGARA_ROLE_CHEF_AGENCE', 15),
    'role_gestionnaire' => (int) env('ANGARA_ROLE_GESTIONNAIRE', 16),
    // profils.id = 17 — instruction dossiers (synonymes : analyste financier, AFE, analyste)
    // Ne jamais retomber à 0 si .env vide ou invalide (siné liste d’affectation vide).
    'role_analyste_financier' => (function () {
        $raw = env('ANGARA_ROLE_ANALYSTE_FINANCIER');
        $id = is_numeric($raw) ? (int) $raw : 0;

        return $id > 0 ? $id : 17;
    })(),
    'role_analyste_risques' => (int) env('ANGARA_ROLE_ANALYSTE_RISQUES', 18),
    // Profil « analyste juridique » (affectation par le resp. juridique). À ne pas confondre avec role_responsable_juridique (10).
    'role_analyste_juridique' => (int) env('ANGARA_ROLE_ANALYSTE_JURIDIQUE', 19),
    'role_analyste_credit' => (int) env('ANGARA_ROLE_ANALYSTE_CREDIT', 20),
    'role_analyste_conformite' => (int) env('ANGARA_ROLE_ANALYSTE_CONFORMITE', 21),
    'role_auditeur' => (int) env('ANGARA_ROLE_AUDITEUR', 22),
    'role_controleur' => (int) env('ANGARA_ROLE_CONTROLEUR', 23),
    'role_chef_filiere' => (int) env('ANGARA_ROLE_CHEF_FILIERE', 24),

    /** Directeur général / adjoint — transmission dossier instruction depuis le pôle risques. */
    'role_dg' => (int) env('ANGARA_ROLE_DG', 4),
    'role_dga' => (int) env('ANGARA_ROLE_DGA', 5),

    /**
     * Emails explicites (séparés par des virgules) pour notifier le circuit prospect
     * lorsqu’aucun utilisateur actif n’est trouvé pour le rôle attendu (ex. base de démo).
     * Exemple : ANGARA_WORKFLOW_PROSPECT_JURIDIQUE_EMAILS=juridique@example.com
     */
    'workflow_prospect_juridique_emails' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ANGARA_WORKFLOW_PROSPECT_JURIDIQUE_EMAILS', ''))
    ))),

    'workflow_prospect_conformite_emails' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ANGARA_WORKFLOW_PROSPECT_CONFORMITE_EMAILS', ''))
    ))),

    /**
     * Seuil (en monnaie locale) au-dessus duquel un dossier d'instruction
     * en cours déclenche une alerte dans le tableau de bord Risques.
     */
    'alerte_engagement_seuil' => (float) env('ANGARA_ALERTE_ENGAGEMENT_SEUIL', 100_000_000),

];
