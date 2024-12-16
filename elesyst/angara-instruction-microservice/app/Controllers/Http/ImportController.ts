import type { HttpContextContract } from '@ioc:Adonis/Core/HttpContext'
import Sekolah  from 'App/Models/Sekolah'
import Application from '@ioc:Adonis/Core/Application'
import Drive from '@ioc:Adonis/Core/Drive'
import Excel   from 'node_modules/exceljs'
import Kepsek from 'App/Models/Kepsek'
import Indicateur from 'App/Models/Indicateur'
import NumberHelper from 'App/Helpers/NumberHelper'

export default class ImportController {

  public async index(ctx: HttpContextContract) {
   const sekolah = await Sekolah.create({
      nama_sekolah:'Angouah Massaga Morel Junior',
      kode_sekolah:'Ingenieur Chercheur'
    })
    console.log(sekolah)
    return sekolah.serialize()
  }

  async getIndicateur(ctx:HttpContextContract){
   // return ctx.params.id
    const indicateur = await Indicateur.findBy('id',ctx.params.id)

    return indicateur!.serialize()
  }

   async createIndicateur(ctx:HttpContextContract){
    let upload = ctx.request.file('upload')
    let dossier_id = ctx.request.input('dossier_id')
    let annee = ctx.request.input('annee')

    await upload!.moveToDisk('./')
    var workbook = new Excel.Workbook()
      workbook = await workbook.xlsx.readFile(upload!.filePath!)
      let bilan = workbook.getWorksheet('Bilan')
      let cpte_resultat = workbook.getWorksheet('Cpte Résultat')
      //const colcp = cpte_resultat?.getColumn('I')
      //return cpte_resultat?.getCell('I44').text.length==0
      let flux_treso = workbook.getWorksheet('Flux Trésorerie')
      var cpv=[
        cpte_resultat!.getCell('I22').text,
        cpte_resultat!.getCell('I23').text,
        cpte_resultat!.getCell('I24').text,
        cpte_resultat!.getCell('I26').text,
        cpte_resultat!.getCell('I27').text,
        cpte_resultat!.getCell('I28').text,
        cpte_resultat!.getCell('I29').text,
        cpte_resultat!.getCell('I30').text,
        cpte_resultat!.getCell('I32').text,
        cpte_resultat!.getCell('I35').text
      ]

      var cpv_1=[
        cpte_resultat!.getCell('K22').text,
        cpte_resultat!.getCell('K23').text,
        cpte_resultat!.getCell('K24').text,
        cpte_resultat!.getCell('K25').text,
        cpte_resultat!.getCell('K26').text,
        cpte_resultat!.getCell('K27').text,
        cpte_resultat!.getCell('K28').text,
        cpte_resultat!.getCell('K29').text,
        cpte_resultat!.getCell('K30').text,
        cpte_resultat!.getCell('K32').text,
        cpte_resultat!.getCell('K35').text
      ]

      const indicateur = await Indicateur.updateOrCreate(
        {
          dossier_id:dossier_id,
          annee:annee
        },{
        ca: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I17').text),
        marge_commerciale: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I13').text),
        va: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I31').text),
        ebe: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I33').text),
        resultat_expl: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I36').text),
        resultat_fin: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I42').text),
        resultat_ao: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I43').text),
        resultat_hao: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('I48').text),
        resultat_net: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I51').text}`),
        val_compt_cci: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I46').text}`),
        prod_cci: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I44').text}`),
        revenus_fin: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I37').text}`),
        gains_change: NumberHelper.convertTextToNumber(`0`),
        transf_charges_fin: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I39').text}`),
        prod_hao: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I45').text}`),
        transf_charges_hao: NumberHelper.convertTextToNumber(`0`),
        frais_fin: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I40').text}`),
        pertes_change: NumberHelper.convertTextToNumber(`0`),
        participation: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I49').text}`),
        impots_resultats: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('I50').text}`),
        distrib_divid: NumberHelper.convertTextToNumber(`${bilan!.getCell('K49').text}`),
        rentab_eco: this.getRentaEco(cpte_resultat!.getCell('I36').text,bilan!.getCell('T12').text,bilan!.getCell('T23').text),
        rentab_fin: this.getRentaFin(cpte_resultat!.getCell('I51').text,bilan!.getCell('T12').text),
        capitaux_propres_res_assim: NumberHelper.convertTextToNumber(`${bilan!.getCell('T22').text}`),
        dettes_fin: NumberHelper.convertTextToNumber(`${bilan!.getCell('T26').text}`),
        actif_immo: NumberHelper.convertTextToNumber(`${bilan!.getCell('K26').text}`),
        actif_circulant_expl: this.getActifCirExpl(bilan!.getCell('K33').text,bilan!.getCell('K27').text),
        passif_circulant_expl: this.getPassifCirExpl(bilan!.getCell('T34').text,bilan!.getCell('T28').text),
        actif_circulant_hao:NumberHelper.convertTextToNumber(`${bilan!.getCell('K27').text}`),
        passif_circulant_hao:NumberHelper.convertTextToNumber(`${bilan!.getCell('T28').text}`),
        controle_treso_net:this.getCtrTreso(bilan!.getCell('K37').text,bilan!.getCell('T37').text),
        flux_treso_act_op:NumberHelper.convertTextToNumber(`${flux_treso!.getCell('I17').text}`),
        flux_treso_act_invest:NumberHelper.convertTextToNumber(`${flux_treso!.getCell('I25').text}`),
        flux_treso_act_fin:NumberHelper.convertTextToNumber(`${flux_treso!.getCell('I39').text}`),
        endettement_fin_brut: this.getEndetBrut(bilan!.getCell('T23').text,bilan!.getCell('T24').text,bilan!.getCell('T37').text),
        treso_actif:NumberHelper.convertTextToNumber(`${bilan!.getCell('K37').text}`),
        stock_moyen:this.getStockMoy(bilan!.getCell('M28').text,bilan!.getCell('K28').text),
        cout_prod_vendu:NumberHelper.sum(cpv),
        ratio_endet_global:this.getRatio_(bilan!.getCell('K39').text,bilan!.getCell('T22').text,bilan!.getCell('K39').text),
        bilan_row_23:NumberHelper.convertTextToNumber(`${bilan!.getCell('T23').text}`),
        bilan_row_24:NumberHelper.convertTextToNumber(`${bilan!.getCell('T24').text}`),
        couverture_frais_fin:this.getRatio(flux_treso!.getCell('I17').text,'',cpte_resultat!.getCell('I40').text),
        solvabilite_glob_rx_liq:this.getRatio(bilan!.getCell('T22').text,'',bilan!.getCell('T39').text),
        liquidite_generale:this.getRatio(bilan!.getCell('K33').text,'',bilan!.getCell('T34').text),
        rentab_eco_ratio:this.getRatio(cpte_resultat!.getCell('I36').text,'',bilan!.getCell('K39').text),
        rentab_fin_ratio:this.divide(bilan!.getCell('T22').text,cpte_resultat!.getCell('I51').text),
        capacite_endettement:this.getRatioC(bilan!.getCell('T22').text,bilan!.getCell('T23').text,bilan!.getCell('T24').text),
        delai_client:this.getRatio(bilan!.getCell('K31').text,'',cpte_resultat!.getCell('I17').text)*365,
        delai_fournisseur:this.getRatioC(bilan!.getCell('T30').text,cpte_resultat!.getCell('I23').text,cpte_resultat!.getCell('I25').text)*365,
        dossier_id:dossier_id,
        annee:annee
      })

      const indicateur_1 = await Indicateur.updateOrCreate(
        {
          dossier_id:dossier_id,
          annee:(annee-1)
        },
        {
        ca: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K17').text),
        marge_commerciale: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K13').text),
        va: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K31').text),
        ebe: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K33').text),
        resultat_expl: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K36').text),
        resultat_fin: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K42').text),
        resultat_ao: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K43').text),
        resultat_hao: NumberHelper.convertTextToNumber(cpte_resultat!.getCell('K48').text),
        resultat_net: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K51').text}`),
        val_compt_cci: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K46').text}`),
        prod_cci: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K44').text}`),
        revenus_fin: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K37').text}`),
        gains_change: NumberHelper.convertTextToNumber(`0`),
        transf_charges_fin: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K39').text}`),
        prod_hao: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K45').text}`),
        transf_charges_hao: NumberHelper.convertTextToNumber(`0`),
        frais_fin: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K40').text}`),
        pertes_change: NumberHelper.convertTextToNumber(`0`),
        participation: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K49').text}`),
        impots_resultats: NumberHelper.convertTextToNumber(`${cpte_resultat!.getCell('K50').text}`),
        distrib_divid: NumberHelper.convertTextToNumber(`${bilan!.getCell('M49').text}`),
        rentab_eco: this.getRentaEco(cpte_resultat!.getCell('K36').text,bilan!.getCell('V12').text,bilan!.getCell('T23').text),
        rentab_fin: this.getRentaFin(cpte_resultat!.getCell('K51').text,bilan!.getCell('V12').text),
        capitaux_propres_res_assim: NumberHelper.convertTextToNumber(`${bilan!.getCell('V22').text}`),
        dettes_fin: NumberHelper.convertTextToNumber(`${bilan!.getCell('V26').text}`),
        actif_immo: NumberHelper.convertTextToNumber(`${bilan!.getCell('M26').text}`),
        actif_circulant_expl: this.getActifCirExpl(bilan!.getCell('M33').text,bilan!.getCell('M27').text),
        passif_circulant_expl: this.getPassifCirExpl(bilan!.getCell('V34').text,bilan!.getCell('V28').text),
        actif_circulant_hao:NumberHelper.convertTextToNumber(`${bilan!.getCell('M27').text}`),
        passif_circulant_hao:NumberHelper.convertTextToNumber(`${bilan!.getCell('V28').text}`),
        controle_treso_net:this.getCtrTreso(bilan!.getCell('M37').text,bilan!.getCell('V37').text),
        flux_treso_act_op:NumberHelper.convertTextToNumber(`${flux_treso!.getCell('K17').text}`),
        flux_treso_act_invest:NumberHelper.convertTextToNumber(`${flux_treso!.getCell('K25').text}`),
        flux_treso_act_fin:NumberHelper.convertTextToNumber(`${flux_treso!.getCell('K39').text}`),
        endettement_fin_brut: this.getEndetBrut(bilan!.getCell('V23').text,bilan!.getCell('V24').text,bilan!.getCell('V37').text),
        treso_actif:NumberHelper.convertTextToNumber(`${bilan!.getCell('M37').text}`),
        stock_moyen:this.getStockMoy(bilan!.getCell('K44').text,bilan!.getCell('M28').text),
        cout_prod_vendu:NumberHelper.sum(cpv_1),
        ratio_endet_global:this.getRatio_(bilan!.getCell('M39').text,bilan!.getCell('V22').text,bilan!.getCell('M39').text),
        bilan_row_23:NumberHelper.convertTextToNumber(`${bilan!.getCell('V23').text}`),
        bilan_row_24:NumberHelper.convertTextToNumber(`${bilan!.getCell('V24').text}`),
        couverture_frais_fin:this.getRatio(flux_treso!.getCell('K17').text,'',cpte_resultat!.getCell('K40').text),
        solvabilite_glob_rx_liq:this.getRatio(bilan!.getCell('V22').text,'',bilan!.getCell('V39').text),
        liquidite_generale:this.getRatio(bilan!.getCell('M33').text,'',bilan!.getCell('V34').text),
        rentab_eco_ratio:this.getRatio(cpte_resultat!.getCell('K36').text,'',bilan!.getCell('M39').text),
        rentab_fin_ratio:this.divide(bilan!.getCell('V22').text,cpte_resultat!.getCell('K51').text),
        capacite_endettement:this.getRatioC(bilan!.getCell('V22').text,bilan!.getCell('V23').text,bilan!.getCell('V24').text),
        delai_client:this.getRatio(bilan!.getCell('M31').text,'',cpte_resultat!.getCell('K17').text)*365,
        delai_fournisseur:this.getRatioC(bilan!.getCell('V30').text,cpte_resultat!.getCell('K23').text,cpte_resultat!.getCell('K25').text)*365,
        dossier_id:dossier_id,
        annee:(annee-1),
        parent_id:indicateur.id
      })
      return ctx.response.ok('ok')
     // return ctx.response.redirect('/')
  }


  private getRatioC(n1,n2,n3){
    const d1 = NumberHelper.convertTextToNumber(n1)
    const d2 = NumberHelper.convertTextToNumber(n2)
    const d3 = NumberHelper.convertTextToNumber(n3)
    if((d2+d3)==0){
      return 0
    }
    return d1/(d2+d3)
  }

  private divide(n1,n2){
    const d1 = NumberHelper.convertTextToNumber(n1)
    const d2 = NumberHelper.convertTextToNumber(n2)
    if(d2==0){
      return 0
    }
    return d1/d2
  }

  private getRatio_(n1,n2,n3){
    const d1 = NumberHelper.convertTextToNumber(n1)
    const d2 = NumberHelper.convertTextToNumber(n2)
    const d3 = NumberHelper.convertTextToNumber(n3)
    if(d3==0){
      return 0
    }
    return (d1-d2)/d3
  }

  private getRatio(n1,n2,n3){
    const d1 = NumberHelper.convertTextToNumber(n1)
    const d2 = NumberHelper.convertTextToNumber(n2)
    const d3 = NumberHelper.convertTextToNumber(n3)
    if(d3==0){
      return 0
    }
    return (d1+d2)/d3
  }

  private getStockMoy(n,d){
    const nm = NumberHelper.convertTextToNumber(n)
    const dv = NumberHelper.convertTextToNumber(d)
    return (nm+dv)/2
  }

  private getEndetBrut(n1,n2,n3){
    return NumberHelper.convertTextToNumber(n1) + NumberHelper.convertTextToNumber(n2) + NumberHelper.convertTextToNumber(n3)
  }

  private getCtrTreso(n1,n2){
    return NumberHelper.convertTextToNumber(n1) - NumberHelper.convertTextToNumber(n2)
  }

  private getActifCirExpl(n1,n2){
    return NumberHelper.convertTextToNumber(n1) - NumberHelper.convertTextToNumber(n2)
  }

  private getPassifCirExpl(n1,n2){
    return NumberHelper.convertTextToNumber(n1) - NumberHelper.convertTextToNumber(n2)
  }

  private getRentaFin(v,d){
    const dm = NumberHelper.convertTextToNumber(d)
    if(dm!=0){
      const n = NumberHelper.convertTextToNumber(v)
      return n/dm
    }else{
      return 0
    }
  }

  private getRentaEco(v,d1,d2){
    const d = NumberHelper.convertTextToNumber(d1)+NumberHelper.convertTextToNumber(d2)
    if(d!=0){
      const n = NumberHelper.convertTextToNumber(v)
      return n/d
    }else{
      return 0
    }
  }

  async import({request, response})
  {
    //return Drive.exists(`uploads/173111.xlsx`)
      let upload  = request.file('upload')
      let fname   = `${new Date().getTime()}.${upload.extname}`
      //let dir     = 'uploads/'


      //move uploaded file into custom folder
     /* await upload.move(Helpers.tmpPath(dir), {
          name: fname
      }) */
       await upload.moveToDisk('./')



      var workbook = new Excel.Workbook()

     workbook = await workbook.xlsx.readFile(upload.filePath)
     let explanation = workbook.getWorksheet('Sheet 1') // get sheet name
      let colComment = explanation!.getColumn('C')
      var data = []
      var seks = []
      var keps :Object[]=[]

    /*  explanation!.eachRow(async(row,index)=>{
        if(index>=11){
          await Sekolah.create({
            nama_sekolah: row.getCell('B'+index).value,
            kode_sekolah: row.getCell('C'+index).value,
          }).then(async(value)=>{
             var inputNama = {
                nama_kepsek: row.getCell('D'+index),
                nip: row.getCell('E'+index),
                id_sekolah: value.id
              }
              await Kepsek.create(inputNama)
          }).catch((reason)=>{
            keps.push({
              error:reason,
              data:{
                nama_kepsek: row.getCell('D'+index),
                nip: row.getCell('E'+index),
              }
            })
          })
        }
      }) */

   colComment.eachCell(async (cell, rowNumber) => {
      if (rowNumber >= 11) {
        let sekolah = explanation!.getCell('B' + rowNumber).value //get cell and the row
        let kode = explanation!.getCell('C' + rowNumber).value
        let nama = explanation!.getCell('D' + rowNumber).value
        let nip = explanation!.getCell('E' + rowNumber).value

        //custom field name in database to variable
        let inputSekolah = {
          nama_sekolah: sekolah,
          kode_sekolah: kode
        }

        let inputNama = {
          nama_kepsek: nama,
          nip: nip,
          id_sekolah: 0
        }

        let resSekolah = await Sekolah.create(inputSekolah)
        .then(async (value)=>{
          inputNama.id_sekolah = value.id
          let resNama = await Kepsek.create(inputNama)
        })
        .catch((reason)=>{
          keps.push({
            error:reason,
            data:inputNama
          })
        })

       // inputNama.id_sekolah = resSekolah.id
        //let resNama = await Kepsek.create(inputNama)
        //console.log('sekolah', resSekolah.toJSON())
      }
    })

    return keps

      //let send = await ImportService.ImportClassification(target)
      //console.log(send)
  }

}
