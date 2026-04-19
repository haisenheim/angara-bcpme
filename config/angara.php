<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rôles métier (table users.role_id = profils.id)
    |--------------------------------------------------------------------------
    | Référentiel historique observé dans angara_demo_db.sql :
    | 10 = Responsable juridique
    | 14 = Analyste financier exploitation
    | 15 = Analyste financier risque / crédit
    | 16 = Analyste juridique
    */
    'role_analyste_financier' => (int) env('ANGARA_ROLE_ANALYSTE_FINANCIER', 14),
    'role_analyste_credit' => (int) env('ANGARA_ROLE_ANALYSTE_CREDIT', 0),
    'role_analyste_juridique' => (int) env('ANGARA_ROLE_ANALYSTE_JURIDIQUE', 16),

    'role_responsable_juridique' => (int) env('ANGARA_ROLE_RESP_JURIDIQUE', 10),
    'role_responsable_conformite' => (int) env('ANGARA_ROLE_RESP_CONFORMITE', 15),

];
