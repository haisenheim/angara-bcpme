<?php

namespace App\Helpers;

class CritereDataHelper {
    public static function getData() {
        return [
            ["sequence" => 18, "percentage" => 3, "label" => "Dynamique de l'Equilibre Financier (FR/BFR)", "field" => "dynamique_equil_fin"],
            ["sequence" => 19, "percentage" => 3, "label" => "Ratio d'endettement global ((Actifs - Capitaux propres)/ Actifs) <1", "field" => "ratio_endet_global"],
            ["sequence" => 20, "percentage" => 4, "label" => "Capacité de Remboursement (DLMT/CAFG)", "field" => "capacite_remb"],
            ["sequence" => 21, "percentage" => 4, "label" => "Couverture des frais financiers (FTAO/FF)", "field" => "couverture_frais_fin"],
            ["sequence" => 22, "percentage" => 4, "label" => "Solvabilité globale Risque liquidatif (Ressources Propres /Total Bilan)", "field" => "solvabilite_glob_rx_liq"],
            ["sequence" => 23, "percentage" => 4, "label" => "Liquidité générale (Ratio de FR= Actifs circulants/ Passifs circulants)>1", "field" => "liquidite_generale"],
            ["sequence" => 24, "percentage" => 5, "label" => "Rentabilité d'exploitation par rapport au CA (EBE/ CA)>30%", "field" => "rentab_expl_ca"],
            ["sequence" => 25, "percentage" => 4, "label" => "Rentabilité économique ( REX/ACTIF)>10%", "field" => "rentab_eco_ratio"],
            ["sequence" => 26, "percentage" => 4, "label" => "Rentabilité Financière (RN/Capitaux propres)>10%", "field" => "rentab_fin_ratio"],
            ["sequence" => 27, "percentage" => 4, "label" => "Capacité d'endettement (Ressources Propres/Dettes structurelles)>1", "field" => "capacite_endettement"],
            ["sequence" => 28, "percentage" => 2, "label" => "Délai Client((créances client /CA)*365)", "field" => "delai_client"],
            ["sequence" => 29, "percentage" => 2, "label" => "Délai Fournisseur((dettes fournisseurs /Achats à crédit)*365)", "field" => "delai_fournisseur"],
            ["sequence" => 30, "percentage" => 2, "label" => "Délai d'Ecoulement des stocks((stock moyen/Coûts des produits vendus)*365)", "field" => "delai_ecoul_stocks"]
        ];
    }

    public static function getNote18($val) {
        if ($val < 0.2) return 10;
        if ($val <= 0.5) return 9;
        if ($val <= 0.8) return 8;
        if ($val <= 1) return 7;
        if ($val <= 1.2) return 6;
        if ($val <= 1.4) return 5;
        if ($val <= 1.6) return 4;
        if ($val <= 1.8) return 3;
        if ($val <= 2) return 2;
        return 1;
    }

    public static function getNote19($val) {
        if ($val < 0.1) return 1;
        if ($val <= 0.2) return 2;
        if ($val <= 0.4) return 3;
        if ($val <= 0.45) return 4;
        if ($val <= 0.49) return 5;
        if ($val <= 0.6) return 6;
        if ($val <= 0.7) return 7;
        if ($val <= 0.8) return 8;
        if ($val <= 1) return 9;
        return 10;
    }

    public static function getNote20($val) {
        if ($val < 1) return 1;
        if ($val < 1.5) return 2;
        if ($val < 2) return 3;
        if ($val < 3) return 4;
        if ($val < 3.4) return 5;
        if ($val < 3.8) return 6;
        if ($val < 4) return 7;
        if ($val < 4.4) return 8;
        if ($val < 5) return 9;
        return 10;
    }

    public static function getNote21($val) {
        if ($val > 6) return 1;
        if ($val > 5) return 2;
        if ($val > 4) return 3;
        if ($val > 3) return 4;
        if ($val == 3) return 5;
        if ($val < 3) return 6;
        if ($val < 2) return 7;
        if ($val < 1.5) return 8;
        if ($val < 1) return 9;
        return 10;
    }

    public static function getNote22($val) {
        if ($val < 3) return 10;
        if ($val < 5) return 9;
        if ($val < 10) return 8;
        if ($val < 15) return 7;
        if ($val < 20) return 6;
        if ($val < 25) return 5;
        if ($val == 25) return 4;
        if ($val > 35) return 1;
        if ($val > 30) return 2;
        if ($val > 25) return 3;
        return 10;
    }

    public static function getNote23($val) {
        switch (true) {
            case $val <= 0.2:
                return 10;
            case $val <= 0.5:
                return 9;
            case $val <= 0.8:
                return 8;
            case $val <= 1:
                return 7;
            case $val <= 1.2:
                return 6;
            case $val <= 1.4:
                return 5;
            case $val <= 1.6:
                return 4;
            case $val <= 1.8:
                return 3;
            case $val <= 2:
                return 2;
            default:
                return 1;
        }
    }

    public static function getNote24($val) {
        switch (true) {
            case $val < 10:
                return 10;
            case $val < 15:
                return 9;
            case $val < 25:
                return 8;
            case $val < 30:
                return 7;
            case $val == 30:
                return 6;
            case $val > 50:
                return 1;
            case $val > 45:
                return 2;
            case $val > 40:
                return 3;
            case $val > 35:
                return 4;
            case $val > 30:
                return 5;
            default:
                return 1;
        }
    }

    public static function getNote25($val) {
        switch (true) {
            case $val < 1:
                return 10;
            case $val < 3:
                return 9;
            case $val < 5:
                return 8;
            case $val < 7.5:
                return 7;
            case $val > 10:
                return 6;
            case $val == 10:
                return 5;
            case $val > 20:
                return 1;
            case $val > 17.5:
                return 2;
            case $val > 15:
                return 3;
            case $val > 12.5:
                return 4;
            default:
                return 1;
        }
    }

    public static function getNote26($val) {
        switch (true) {
            case $val < 1:
                return 10;
            case $val < 3:
                return 9;
            case $val < 5:
                return 8;
            case $val < 7.5:
                return 7;
            case $val < 10:
                return 6;
            case $val == 10:
                return 5;
            case $val > 20:
                return 1;
            case $val > 17.5:
                return 2;
            case $val > 15:
                return 3;
            case $val > 12.5:
                return 4;
            default:
                return 1;
        }
    }

    public static function getNote27($val) {
        switch (true) {
            case $val < 0.2:
                return 10;
            case $val < 0.5:
                return 9;
            case $val < 0.8:
                return 8;
            case $val < 1:
                return 7;
            case $val == 1:
                return 6;
            case $val > 2:
                return 1;
            case $val > 1.8:
                return 2;
            case $val > 1.6:
                return 3;
            case $val > 1.4:
                return 4;
            case $val > 1.2:
                return 5;
            default:
                return 1;
        }
    }

    public static function getNote28($val) {
        switch (true) {
            case $val < 10:
                return 1;
            case $val < 20:
                return 2;
            case $val < 30:
                return 3;
            case $val < 40:
                return 4;
            case $val < 50:
                return 5;
            case $val < 60:
                return 6;
            case $val < 70:
                return 7;
            case $val < 80:
                return 8;
            case $val < 90:
                return 9;
            default:
                return 10;
        }
    }

    public static function getNote29($val) {
        switch (true) {
            case $val < 10:
                return 10;
            case $val <= 20:
                return 9;
            case $val <= 30:
                return 8;
            case $val <= 40:
                return 7;
            case $val <= 50:
                return 6;
            case $val <= 60:
                return 5;
            case $val <= 70:
                return 4;
            case $val <= 80:
                return 3;
            case $val <= 90:
                return 2;
            default:
                return 10;
        }
    }

    public static function getNote30($val) {
        switch (true) {
            case $val < 20:
                return 1;
            case $val < 40:
                return 2;
            case $val < 60:
                return 3;
            case $val < 80:
                return 4;
            case $val < 100:
                return 5;
            case $val < 120:
                return 6;
            case $val < 140:
                return 7;
            case $val < 160:
                return 8;
            case $val < 180:
                return 9;
            default:
                return 10;
        }
    }
}

?>
