import BaseSchema from '@ioc:Adonis/Lucid/Schema'

export default class extends BaseSchema {
  protected tableName = 'indicateurs_financiers'

  public async up () {
    this.schema.createTable(this.tableName, (table) => {
      table.increments('id')
      table.double('ca').defaultTo(0)
      table.double('marge_commerciale').defaultTo(0)
      table.double('va').defaultTo(0)
      table.double('ebe').defaultTo(0)
      table.double('resultat_expl').defaultTo(0)
      table.double('resultat_fin').defaultTo(0)
      table.double('resultat_ao').defaultTo(0)
      table.double('resultat_hao').defaultTo(0)
      table.double('resultat_net').defaultTo(0)
      table.double('val_compt_cci').defaultTo(0)
      table.double('prod_cci').defaultTo(0)
      table.double('revenus_fin').defaultTo(0)
      table.double('gains_change').defaultTo(0)
      table.double('transf_charges_fin').defaultTo(0)
      table.double('prod_hao').defaultTo(0)
      table.double('transf_charges_hao').defaultTo(0)
      table.double('frais_fin').defaultTo(0)
      table.double('pertes_change').defaultTo(0)
      table.double('participation').defaultTo(0)
      table.double('impots_resultats').defaultTo(0)
      table.double('distrib_divid').defaultTo(0)
      table.double('capitaux_propres_res_assim').defaultTo(0)
      table.double('dettes_fin').defaultTo(0)
      table.double('actif_immo').defaultTo(0)
      table.double('actif_circulant_expl').defaultTo(0)
      table.double('passif_circulant_expl').defaultTo(0)
      table.double('actif_circulant_hao').defaultTo(0)
      table.double('passif_circulant_hao').defaultTo(0)
      table.double('controle_treso_net').defaultTo(0)
      table.double('flux_treso_act_op').defaultTo(0)
      table.double('flux_treso_act_invest').defaultTo(0)
      table.double('flux_treso_act_fin').defaultTo(0)
      table.double('endettement_fin_brut').defaultTo(0)
      table.double('treso_actif').defaultTo(0)
      table.double('stock_moyen').defaultTo(0)
      table.double('cout_prod_vendu').defaultTo(0)
      table.double('ratio_endet_global').defaultTo(0)
      //table.double('capacite_remb').defaultTo(0)
      table.double('couverture_frais_fin').defaultTo(0)
      table.double('solvabilite_glob_rx_liq').defaultTo(0)
      table.double('liquidite_generale').defaultTo(0)
      //table.double('rentab_expl_ca').defaultTo(0)
      table.double('rentab_eco').defaultTo(0)
      table.double('rentab_fin').defaultTo(0)
      table.double('capacite_endettement').defaultTo(0)
      table.double('delai_client').defaultTo(0)
      table.double('delai_fournisseur').defaultTo(0)
      //table.double('delai_ecoul_stocks').defaultTo(0)
      table.double('rentab_eco_ratio').defaultTo(0)
      table.double('rentab_fin_ratio').defaultTo(0)
      table.double('bilan_row_23').defaultTo(0)
      table.double('bilan_row_24').defaultTo(0)
      table.integer('dossier_id').defaultTo(0)
      table.integer('user_id').defaultTo(0)
      table.integer('parent_id').defaultTo(0)
      table.integer('annee').defaultTo(0)
      /**
       * Uses timestamptz for PostgreSQL and DATETIME2 for MSSQL
       */
      table.timestamp('created_at', { useTz: true })
      table.timestamp('updated_at', { useTz: true })
    })
  }

  public async down () {
    this.schema.dropTable(this.tableName)
  }
}
