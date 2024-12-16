import type { HttpContextContract } from '@ioc:Adonis/Core/HttpContext'
import Indicateur from 'App/Models/Indicateur'

export default class DossiersController {

  public async find(ctx:HttpContextContract){
    const id = ctx.request.param('id')
    var indicateurs = await Indicateur.query().where('dossier_id', '=', id)
    var variations :any[] = []
    const annees = indicateurs.map((value,index)=>{
      console.log(index)
      return value.annee
    }).sort()
   // return annees;
    for(var i=0;i<annees.length-1;i++){
      var n = annees[i+1]
      var n_1 = annees[i]
     var  indicateur_n = indicateurs.filter((ind)=>ind.annee==n)[0]
     var  indicateur_n_1 = indicateurs.filter((ind)=>ind.annee==n_1)[0]
     if((indicateur_n!=undefined) && (indicateur_n_1!=undefined)){
        var gap = {
          ca:indicateur_n_1.ca!=0?Math.round((indicateur_n.ca-indicateur_n_1.ca)*100/indicateur_n_1.ca):0,
          marge_commerciale:indicateur_n_1.marge_commerciale!=0?Math.round((indicateur_n.marge_commerciale-indicateur_n_1.marge_commerciale)*100/indicateur_n_1.marge_commerciale):0,
          va:indicateur_n_1.va!=0?Math.round((indicateur_n.va-indicateur_n_1.va)*100/indicateur_n_1.va):0,
          ebe:indicateur_n_1.ebe!=0?Math.round((indicateur_n.ebe-indicateur_n_1.ebe)*100/indicateur_n_1.ebe):0,
          resultat_expl:indicateur_n_1.resultat_expl!=0?Math.round((indicateur_n.resultat_expl-indicateur_n_1.resultat_expl)*100/indicateur_n_1.resultat_expl):0,
          resultat_fin:indicateur_n_1.resultat_fin!=0?Math.round((indicateur_n.resultat_fin-indicateur_n_1.resultat_fin)*100/indicateur_n_1.resultat_fin):0,
          resultat_ao:indicateur_n_1.resultat_ao!=0?Math.round((indicateur_n.resultat_ao-indicateur_n_1.resultat_ao)*100/indicateur_n_1.resultat_ao):0,
          resultat_hao:indicateur_n_1.resultat_hao!=0?Math.round((indicateur_n.resultat_hao-indicateur_n_1.resultat_hao)*100/indicateur_n_1.resultat_hao):0,
          resultat_net:indicateur_n_1.resultat_net!=0?Math.round((indicateur_n.resultat_net-indicateur_n_1.resultat_net)*100/indicateur_n_1.resultat_net):0,
          valeurs_compt_cci:indicateur_n_1.val_compt_cci!=0?Math.round((indicateur_n.val_compt_cci-indicateur_n_1.val_compt_cci)*100/indicateur_n_1.val_compt_cci):0,
          prod_cci:indicateur_n_1.prod_cci!=0?Math.round((indicateur_n.prod_cci-indicateur_n_1.prod_cci)*100/indicateur_n_1.prod_cci):0,
          capacite_auto_fin_expl:indicateur_n_1.capacite_autofin_expl!=0?Math.round((indicateur_n.capacite_autofin_expl-indicateur_n_1.capacite_autofin_expl)*100/indicateur_n_1.capacite_autofin_expl):0,
          revenus_fin:indicateur_n_1.revenus_fin!=0?Math.round((indicateur_n.revenus_fin-indicateur_n_1.revenus_fin)*100/indicateur_n_1.revenus_fin):0,
          gains_change:indicateur_n_1.gains_change!=0?Math.round((indicateur_n.gains_change-indicateur_n_1.gains_change)*100/indicateur_n_1.gains_change):0,
          transf_charges_fin:indicateur_n_1.transf_charges_fin!=0?Math.round((indicateur_n.transf_charges_fin-indicateur_n_1.transf_charges_fin)*100/indicateur_n_1.transf_charges_fin):0,
          prod_hao:indicateur_n_1.prod_hao!=0?Math.round((indicateur_n.prod_hao-indicateur_n_1.prod_hao)*100/indicateur_n_1.prod_hao):0,
          transf_charges_hao:indicateur_n_1.transf_charges_hao!=0?Math.round((indicateur_n.transf_charges_hao-indicateur_n_1.transf_charges_hao)*100/indicateur_n_1.transf_charges_hao):0,
          frais_fin:indicateur_n_1.frais_fin!=0?Math.round((indicateur_n.frais_fin-indicateur_n_1.frais_fin)*100/indicateur_n_1.frais_fin):0,
          pertes_change:indicateur_n_1.pertes_change!=0?Math.round((indicateur_n.pertes_change-indicateur_n_1.pertes_change)*100/indicateur_n_1.pertes_change):0,
          participation:indicateur_n_1.participation!=0?Math.round((indicateur_n.participation-indicateur_n_1.participation)*100/indicateur_n_1.participation):0,
          impots_resultats:indicateur_n_1.impots_resultats!=0?Math.round((indicateur_n.impots_resultats-indicateur_n_1.impots_resultats)*100/indicateur_n_1.impots_resultats):0,
          capacite_autofin_globale:indicateur_n_1.capacite_autofin_globale!=0?Math.round((indicateur_n.capacite_autofin_globale-indicateur_n_1.capacite_autofin_globale)*100/indicateur_n_1.capacite_autofin_globale):0,
          distrib_divid:indicateur_n_1.distrib_divid!=0?Math.round((indicateur_n.distrib_divid-indicateur_n_1.distrib_divid)*100/indicateur_n_1.distrib_divid):0,
          autofinancement:indicateur_n_1.autofinancement!=0?Math.round((indicateur_n.autofinancement-indicateur_n_1.autofinancement)*100/indicateur_n_1.autofinancement):0,
          rentab_eco:indicateur_n_1.rentab_eco!=0?Math.round((indicateur_n.rentab_eco-indicateur_n_1.rentab_eco)*100/indicateur_n_1.rentab_eco):0,
          rentab_fin:indicateur_n_1.rentab_fin!=0?Math.round((indicateur_n.rentab_fin-indicateur_n_1.rentab_fin)*100/indicateur_n_1.rentab_fin):0,
          capitaux_propres_res_assim:indicateur_n_1.capitaux_propres_res_assim!=0?Math.round((indicateur_n.capitaux_propres_res_assim-indicateur_n_1.capitaux_propres_res_assim)*100/indicateur_n_1.capitaux_propres_res_assim):0,
          dettes_fin:indicateur_n_1.dettes_fin!=0?Math.round((indicateur_n.dettes_fin-indicateur_n_1.dettes_fin)*100/indicateur_n_1.dettes_fin):0,
          ressources_stables:indicateur_n_1.ressources_stables!=0?Math.round((indicateur_n.ressources_stables-indicateur_n_1.ressources_stables)*100/indicateur_n_1.ressources_stables):0,
          actif_immo:indicateur_n_1.actif_immo!=0?Math.round((indicateur_n.actif_immo-indicateur_n_1.actif_immo)*100/indicateur_n_1.actif_immo):0,
          fonds_roulement:indicateur_n_1.fonds_roulement!=0?Math.round((indicateur_n.fonds_roulement-indicateur_n_1.fonds_roulement)*100/indicateur_n_1.fonds_roulement):0,
          actif_circulant_expl:indicateur_n_1.actif_circulant_expl!=0?Math.round((indicateur_n.actif_circulant_expl-indicateur_n_1.actif_circulant_expl)*100/indicateur_n_1.actif_circulant_expl):0,
          passif_circulant_expl:indicateur_n_1.passif_circulant_expl!=0?Math.round((indicateur_n.passif_circulant_expl-indicateur_n_1.passif_circulant_expl)*100/indicateur_n_1.passif_circulant_expl):0,
          besoin_financement_expl:indicateur_n_1.besoin_financement_expl!=0?Math.round((indicateur_n.besoin_financement_expl-indicateur_n_1.besoin_financement_expl)*100/indicateur_n_1.besoin_financement_expl):0,
          actif_circulant_hao:indicateur_n_1.actif_circulant_hao!=0?Math.round((indicateur_n.actif_circulant_hao-indicateur_n_1.actif_circulant_hao)*100/indicateur_n_1.actif_circulant_hao):0,
          passif_circulant_hao:indicateur_n_1.passif_circulant_hao!=0?Math.round((indicateur_n.passif_circulant_hao-indicateur_n_1.passif_circulant_hao)*100/indicateur_n_1.passif_circulant_hao):0,
          besoin_financement_hao:indicateur_n_1.besoin_financement_hao!=0?Math.round((indicateur_n.besoin_financement_hao-indicateur_n_1.besoin_financement_hao)*100/indicateur_n_1.besoin_financement_hao):0,
          besoin_financement_global:indicateur_n_1.besoin_financement_global!=0?Math.round((indicateur_n.besoin_financement_global-indicateur_n_1.besoin_financement_global)*100/indicateur_n_1.besoin_financement_global):0,
          tresorerie_net:indicateur_n_1.tresorerie_net!=0?Math.round((indicateur_n.tresorerie_net-indicateur_n_1.tresorerie_net)*100/indicateur_n_1.tresorerie_net):0,
          controle_treso_net:indicateur_n_1.controle_treso_net!=0?Math.round((indicateur_n.controle_treso_net-indicateur_n_1.controle_treso_net)*100/indicateur_n_1.controle_treso_net):0,
          flux_treso_act_op:indicateur_n_1.flux_treso_act_op!=0?Math.round((indicateur_n.flux_treso_act_op-indicateur_n_1.flux_treso_act_op)*100/indicateur_n_1.flux_treso_act_op):0,
          flux_treso_act_invest:indicateur_n_1.flux_treso_act_invest!=0?Math.round((indicateur_n.flux_treso_act_invest-indicateur_n_1.flux_treso_act_invest)*100/indicateur_n_1.flux_treso_act_invest):0,
          flux_treso_act_fin:indicateur_n_1.flux_treso_act_fin!=0?Math.round((indicateur_n.flux_treso_act_fin-indicateur_n_1.flux_treso_act_fin)*100/indicateur_n_1.flux_treso_act_fin):0,
          variation_treso_nette_periode:indicateur_n_1.variation_treso_nette_periode!=0?Math.round((indicateur_n.variation_treso_nette_periode-indicateur_n_1.variation_treso_nette_periode)*100/indicateur_n_1.variation_treso_nette_periode):0,
          endettement_fin_brut:indicateur_n_1.endettement_fin_brut!=0?Math.round((indicateur_n.endettement_fin_brut-indicateur_n_1.endettement_fin_brut)*100/indicateur_n_1.endettement_fin_brut):0,
          treso_actif:indicateur_n_1.treso_actif!=0?Math.round((indicateur_n.treso_actif-indicateur_n_1.treso_actif)*100/indicateur_n_1.treso_actif):0,
          endettement_fin_net:indicateur_n_1.endettement_fin_net!=0?Math.round((indicateur_n.endettement_fin_net-indicateur_n_1.endettement_fin_net)*100/indicateur_n_1.endettement_fin_net):0,
          stock_moyen:indicateur_n_1.stock_moyen!=0?Math.round((indicateur_n.stock_moyen-indicateur_n_1.stock_moyen)*100/indicateur_n_1.stock_moyen):0,
          cout_produits_vendus:indicateur_n_1.cout_prod_vendu!=0?Math.round((indicateur_n.cout_prod_vendu-indicateur_n_1.cout_prod_vendu)*100/indicateur_n_1.cout_prod_vendu):0,
          dynamique_equil_fin:indicateur_n_1.dynamique_equil_fin!=0?Math.round((indicateur_n.dynamique_equil_fin-indicateur_n_1.dynamique_equil_fin)*100/indicateur_n_1.dynamique_equil_fin):0,
          ratio_endettement_global:indicateur_n_1.ratio_endet_global!=0?Math.round((indicateur_n.ratio_endet_global-indicateur_n_1.ratio_endet_global)*100/indicateur_n_1.ratio_endet_global):0,
          capacite_remboursement:indicateur_n_1.capacite_remb!=0?Math.round((indicateur_n.capacite_remb-indicateur_n_1.capacite_remb)*100/indicateur_n_1.capacite_remb):0,
          couverture_frais_financiers:indicateur_n_1.couverture_frais_fin!=0?Math.round((indicateur_n.couverture_frais_fin-indicateur_n_1.couverture_frais_fin)*100/indicateur_n_1.couverture_frais_fin):0,
          solvabilite_globale_rx_liq:indicateur_n_1.solvabilite_glob_rx_liq!=0?Math.round((indicateur_n.solvabilite_glob_rx_liq-indicateur_n_1.solvabilite_glob_rx_liq)*100/indicateur_n_1.solvabilite_glob_rx_liq):0,
          liquidite_generale:indicateur_n_1.liquidite_generale!=0?Math.round((indicateur_n.liquidite_generale-indicateur_n_1.liquidite_generale)*100/indicateur_n_1.liquidite_generale):0,
          rentabilite_expl_ca:indicateur_n_1.rentab_expl_ca!=0?Math.round((indicateur_n.rentab_expl_ca-indicateur_n_1.rentab_expl_ca)*100/indicateur_n_1.rentab_expl_ca):0,
          rentabilite_eco_ratio:indicateur_n_1.rentab_eco_ratio!=0?Math.round((indicateur_n.rentab_eco_ratio-indicateur_n_1.rentab_eco_ratio)*100/indicateur_n_1.rentab_eco_ratio):0,
          rentabilite_fin_ratio:indicateur_n_1.rentab_fin_ratio!=0?Math.round((indicateur_n.rentab_fin_ratio-indicateur_n_1.rentab_fin_ratio)*100/indicateur_n_1.rentab_fin_ratio):0,
          capacite_endettement:indicateur_n_1.capacite_endettement!=0?Math.round((indicateur_n.capacite_endettement-indicateur_n_1.capacite_endettement)*100/indicateur_n_1.capacite_endettement):0,
          delai_client:indicateur_n_1.delai_client!=0?Math.round((indicateur_n.delai_client-indicateur_n_1.delai_client)*100/indicateur_n_1.delai_client):0,
          delai_fournisseur:indicateur_n_1.delai_fournisseur!=0?Math.round((indicateur_n.delai_fournisseur-indicateur_n_1.delai_fournisseur)*100/indicateur_n_1.delai_fournisseur):0,
          delai_ecoulemet_stock:indicateur_n_1.delai_ecoul_stocks!=0?Math.round((indicateur_n.delai_ecoul_stocks-indicateur_n_1.delai_ecoul_stocks)*100/indicateur_n_1.delai_ecoul_stocks):0,
        }

        variations.push({
          periode:`${n_1}-${n}`,
          variation:gap
        })
     }


    }
    var data = {
      exercices: indicateurs.map((value,index)=>{
                  return value.serialize()
                  }),
      variations:variations,
    }

    return data;
  }

  
}
