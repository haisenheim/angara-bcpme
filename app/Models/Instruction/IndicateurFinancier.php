<?php

namespace App\Models\Instruction;

use App\Helpers\CritereDataHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicateurFinancier extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'indicateurs_financiers';



    public function getDelaiEcoulStocksAttribute()
    {
        return $this->cout_prod_vendu == 0 ? 0 : ($this->stock_moyen / $this->cout_prod_vendu) * 365;
    }

    public function getRentabExplCaAttribute()
    {
        return $this->ebe == 0 ? 0 : $this->ca / $this->ebe;
    }

    public function getCapaciteAutofinExplAttribute()
    {
        return $this->ebe + $this->val_compt_cci - $this->prod_cci;
    }

    public function getCapaciteAutofinGlobaleAttribute()
    {
        return $this->capacite_autofin_expl
            + ($this->revenus_fin + $this->gains_change + $this->transf_charges_fin + $this->prod_hao + $this->transf_charges_hao)
            - ($this->frais_fin + $this->pertes_change + $this->participation + $this->impots_resultats);
    }

    public function getAutofinancementAttribute()
    {
        return $this->capacite_autofin_globale - $this->distrib_divid;
    }

      // Ressources Stables
      public function getRessourcesStablesAttribute()
      {
          return $this->capitaux_propres_res_assim + $this->dettes_fin;
      }

      // Fonds de Roulement
      public function getFondsRoulementAttribute()
      {
          return $this->ressources_stables - $this->actif_immo;
      }

      // Besoin en financement (Exploitation)
      public function getBesoinFinancementExplAttribute()
      {
          return $this->actif_circulant_expl - $this->passif_circulant_expl;
      }

      // Besoin en financement (Hors exploitation)
      public function getBesoinFinancementHaoAttribute()
      {
          return $this->actif_circulant_hao - $this->passif_circulant_hao;
      }

      // Besoin en financement global
      public function getBesoinFinancementGlobalAttribute()
      {
          return $this->besoin_financement_expl + $this->besoin_financement_hao;
      }

      // Trésorerie nette
      public function getTresorerieNetAttribute()
      {
          return $this->fonds_roulement - $this->besoin_financement_global;
      }

      // Variation de trésorerie nette sur la période
      public function getVariationTresoNettePeriodeAttribute()
      {
          return $this->flux_treso_act_op - $this->flux_treso_act_invest + $this->flux_treso_act_fin;
      }

      // Endettement financier net
      public function getEndettementFinNetAttribute()
      {
          return $this->endettement_fin_brut - $this->treso_actif;
      }

      // Dynamique d'équilibre financier
      public function getDynamiqueEquilFinAttribute()
      {
          if ($this->besoin_financement_global == 0) {
              return 0;
          }
          return $this->fonds_roulement / $this->besoin_financement_global;
      }

      // Notation
      public function getNotationAttribute()
      {
          $criteres = CritereDataHelper::getData();
          $notes = [];

          foreach ($criteres as $critere) {
              $val = 0;
              $note = 0;

              switch ($critere['sequence']) {
                  case 18:
                      $val = round($this->dynamique_equil_fin);
                      $note = CritereDataHelper::getNote18($val);
                      break;
                  case 19:
                      $val = round($this->ratio_endet_global);
                      $note = CritereDataHelper::getNote19($val);
                      break;
                  case 20:
                      $val = round($this->capacite_remb);
                      $note = CritereDataHelper::getNote20($val);
                      break;
                  case 21:
                      $val = round($this->couverture_frais_fin);
                      $note = CritereDataHelper::getNote21($val);
                      break;
                  case 22:
                      $val = round($this->solvabilite_glob_rx_liq * 100);
                      $note = CritereDataHelper::getNote22($val);
                      break;
                  case 23:
                      $val = round($this->liquidite_generale);
                      $note = CritereDataHelper::getNote23($val);
                      break;
                  case 24:
                      $val = $this->rentab_expl_ca;
                      $note = CritereDataHelper::getNote24($val);
                      break;
                  case 25:
                      $val = $this->rentab_eco_ratio * 100;
                      $note = CritereDataHelper::getNote25($val);
                      break;
                  case 26:
                      $val = $this->rentab_fin_ratio;
                      $note = CritereDataHelper::getNote26($val);
                      break;
                  case 27:
                      $val = $this->capacite_endettement;
                      $note = CritereDataHelper::getNote27($val);
                      break;
                  case 28:
                      $val = $this->delai_client;
                      $note = CritereDataHelper::getNote28($val);
                      break;
                  case 29:
                      $val = $this->delai_fournisseur;
                      $note = CritereDataHelper::getNote29($val);
                      break;
                  case 30:
                      $val = $this->delai_ecoul_stocks;
                      $note = CritereDataHelper::getNote30($val);
                      break;
              }

              $notes[] = [
                  'sequence' => $critere['sequence'],
                  'critere' => $critere['label'],
                  'valeur' => $val,
                  'note' => $note,
                  'pondere' => $note * $critere['percentage'] / 100,
                  'pourcentage' => $critere['percentage']
              ];
          }

          return [
              'details' => $notes,
              'note' => array_reduce($notes, fn($c, $n) => $c + $n['pondere'], 0)
          ];
      }

}
