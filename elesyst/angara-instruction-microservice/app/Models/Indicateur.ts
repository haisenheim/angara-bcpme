import { DateTime } from 'node_modules/@types/luxon'
import { BaseModel, column, computed } from '@ioc:Adonis/Lucid/Orm'
import CritereDataHelper from 'App/Helpers/CritereDataHelper'

export default class Indicateur extends BaseModel {

  public static table ='indicateurs_financiers'

  @column({ isPrimary: true })
  public id: number

  @column()
  public ca:number

  @column()
  public marge_commerciale:number

  @column()
  public va:number

  @column()
  public ebe:number

  @column()
  public resultat_expl:number

  @column()
  public resultat_fin:number

  @column()
  public resultat_ao:number

  @column()
  public resultat_hao:number

  @column()
  public resultat_net:number

  @column()
  public val_compt_cci:number

  @column()
  public prod_cci:number

  @column()
  public revenus_fin:number

  @column()
  public gains_change:number

  @column()
  public transf_charges_fin:number

  @column()
  public prod_hao:number

  @column()
  public transf_charges_hao:number

  @column()
  public frais_fin:number

  @column()
  public pertes_change:number

  @column()
  public participation:number

  @column()
  public impots_resultats:number

  @column()
  public distrib_divid:number

  @column()
  public capitaux_propres_res_assim:number

  @column()
  public dettes_fin:number

  @column()
  public actif_immo:number

  @column()
  public actif_circulant_expl:number

  @column()
  public passif_circulant_expl:number

  @column()
  public actif_circulant_hao:number

  @column()
  public passif_circulant_hao:number

  @column()
  public controle_treso_net:number

  @column()
  public flux_treso_act_op:number

  @column()
  public flux_treso_act_invest:number

  @column()
  public flux_treso_act_fin:number

  @column()
  public treso_actif:number

  @column()
  public endettement_fin_brut:number

  @column()
  public stock_moyen:number

  @column()
  public cout_prod_vendu:number


  @column()
  public ratio_endet_global:number

  @column()
  public couverture_frais_fin:number

  @column()
  public solvabilite_glob_rx_liq:number

  @column()
  public liquidite_generale:number

  @column()
  public rentab_eco:number

  @column()
  public rentab_fin:number

  @column()
  public capacite_endettement:number
  @column()

  public delai_client:number

  @column()
  public delai_fournisseur:number

  @column()
  public rentab_eco_ratio:number

  @column()
  public rentab_fin_ratio:number

  @column()
  public dossier_id:number

  @column()
  public user_id:number

  @column()
  public parent_id:number

  @column()
  public annee:number

  @column({ serializeAs: null })
  public bilan_row_23:number

  @column({ serializeAs: null })
  public bilan_row_24:number

  @column.dateTime({ autoCreate: true })
  public createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  public updatedAt: DateTime

  @computed()
  public get delai_ecoul_stocks():number{
    if(this.cout_prod_vendu==0){
      return 0
    }
    return (this.stock_moyen/this.cout_prod_vendu)*365
  }

  @computed()
  public get rentab_expl_ca(){
    if(this.ebe == 0){
      return 0
    }
    return this.ca / this.ebe
  }

  @computed()
  public get capacite_remb(){
    if(this.capacite_autofin_globale == 0){
      return 0
    }
    return (this.bilan_row_23 + this.bilan_row_24)/this.capacite_autofin_globale
  }

  @computed()
  public get capacite_autofin_expl(){
    return this.ebe + this.val_compt_cci - this.prod_cci
  }

  @computed()
  public get capacite_autofin_globale(){
    return this.capacite_autofin_expl
            + (this.revenus_fin + this.gains_change+this.transf_charges_fin+this.prod_hao+this.transf_charges_hao)
            - (this.frais_fin + this.pertes_change + this.participation+this.impots_resultats)
  }

  @computed()
  public get autofinancement(){
    return this.capacite_autofin_globale - this.distrib_divid
  }

  @computed()
  public get ressources_stables(){
    return this.capitaux_propres_res_assim + this.dettes_fin
  }

  @computed()
  public get fonds_roulement(){
    return this.ressources_stables - this.actif_immo
  }

  @computed()
  public get besoin_financement_expl(){
    return this.actif_circulant_expl - this.passif_circulant_expl
  }

  @computed()
  public get besoin_financement_hao(){
    return this.actif_circulant_hao - this.passif_circulant_hao
  }

  @computed()
  public get besoin_financement_global(){
    return this.besoin_financement_expl + this.besoin_financement_hao
  }

  @computed()
  public get tresorerie_net(){
    return this.fonds_roulement - this.besoin_financement_global
  }

  @computed()
  public get variation_treso_nette_periode(){
    return this.flux_treso_act_op - this.flux_treso_act_invest + this.flux_treso_act_fin
  }

  @computed()
  public get endettement_fin_net(){
    return this.endettement_fin_brut - this.treso_actif
  }

  @computed()
  public get dynamique_equil_fin(){
    if (this.besoin_financement_global == 0){
      return 0
    }
    return this.fonds_roulement/this.besoin_financement_global
  }

  @computed()
  public get notation(){
    const criteres = CritereDataHelper.data
    var notes:any[] = []

    criteres.forEach(critere => {
      var val = 0  //this.$getAttribute(critere.field)
      var note = 0
      switch (critere.sequence){
        case 18:
          val = Math.round(this.dynamique_equil_fin)
          note = CritereDataHelper.getNote18(val)
          break
        case 19:
          val = Math.round(this.ratio_endet_global)
          note = CritereDataHelper.getNote19(val)
          break
        case 20:
          val = Math.round(this.capacite_remb)
          note = CritereDataHelper.getNote20(val)
          break
        case 21:
          val = Math.round(this.couverture_frais_fin)
          note = CritereDataHelper.getNote21(val)
          break
        case 22:
          val = Math.round(this.solvabilite_glob_rx_liq*100)
          note = CritereDataHelper.getNote22(val)
          break
        case 23:
          val = Math.round(this.liquidite_generale)
          note = CritereDataHelper.getNote23(val)
          break
        case 24:
          val = this.rentab_expl_ca
          note = CritereDataHelper.getNote24(val)
          break
        case 25:
          val = this.rentab_eco_ratio * 100
          note = CritereDataHelper.getNote25(val)
          break
        case 26:
          val = this.rentab_fin_ratio
          note = CritereDataHelper.getNote26(val)
          break
        case 27:
          val = this.capacite_endettement
          note = CritereDataHelper.getNote27(val)
          break
        case 28:
          val = this.delai_client
          note = CritereDataHelper.getNote28(val)
          break
        case 29:
          val = this.delai_fournisseur
          note = CritereDataHelper.getNote29(val)
          break
        case 30:
          val = this.delai_ecoul_stocks
          note = CritereDataHelper.getNote30(val)
          break


      }
         notes.push({
            sequence:critere.sequence,
            critere:critere.label,
            valeur:val,
            note:note,
            pondere:note * critere.percentage /100,
            pourcentage:critere.percentage
         })
    });
    return {
      details:notes,
      note:notes.reduce((c,n)=>c+n.pondere,0)
    }
  }



}
