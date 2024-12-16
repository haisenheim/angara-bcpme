export default class CritereDataHelper{
  static get data(){
    return [
      {
        sequence:18,
        percentage:3,
        label:"Dynamique de l'Equilibre Financier (FR/BFR)",
        field:"dynamique_equil_fin"
      },
      {
        sequence:19,
        percentage:3,
        label:"Ratio d'endettement global ((Actifs - Capitaux propres)/ Actifs) <1",
        field:"ratio_endet_global"
      },
      {
        sequence:20,
        percentage:4,
        label:"Capacité de  Remboursement (DLMT/CAFG)",
        field:"capacite_remb"
      },
      {
        sequence:21,
        percentage:4,
        label:"Couverture des frais financiers (FTAO/FF)",
        field:"couverture_frais_fin"
      },
      {
        sequence:22,
        percentage:4,
        label:"Solvabilité globale  Risque liquidatif (Ressources Propres /Total Bilan)",
        field:"solvabilite_glob_rx_liq"
      },
      {
        sequence:23,
        percentage:4,
        label:"Liquidité générale (Ratio de FR= Actifs circulants/ Passifs circulants)>1",
        field:"liquidite_generale"
      },
      {
        sequence:24,
        percentage:5,
        label:"Rentabilité d'exploitation par rapport au CA (EBE/ CA)>30%",
        field:"rentab_expl_ca"
      },
      {
        sequence:25,
        percentage:4,
        label:"Rentabilité économique ( REX/ACTIF)>10%",
        field:"rentab_eco_ratio"
      },
      {
        sequence:26,
        percentage:4,
        label:"Rentabilité Financière (RN/Capitaux propres)>10%",
        field:"rentab_fin_ratio"
      },
      {
        sequence:27,
        percentage:4,
        label:"Capacité d'endettement (Ressources Propres/Dettes structurelles)>1",
        field:"capacite_endettement"
      },
      {
        sequence:28,
        percentage:2,
        label:"Délai Client((créances client /CA)*365)",
        field:"delai_client"
      },
      {
        sequence:29,
        percentage:2,
        label:"Délai Fournisseur((dettes fournisseurs /Achats à crédit)*365)",
        field:"delai_fournisseur"
      },
      {
        sequence:30,
        percentage:2,
        label:"Délai d'Ecoulement des stocks((stock moyen/Coûts des produits vendus)*365)",
        field:"delai_ecoul_stocks"
      },
    ]
  }

  static getNote18(val){ //Dynamique de l'Equilibre Financier (FR/BFR)
    switch(true){
      case val<0.2:
        return 10;
      case val<=0.5:
        return 9;
      case val<=0.8:
        return 8
      case val<=1:
        return 7
      case val<=1.2:
        return 6
      case val<=1.4:
        return 5
      case val<=1.6:
        return 4
      case val<=1.8:
        return 3
      case val<=2:
        return 2
      default:
        return 1
    }
  }

  static getNote19(val){ //Ratio d'endettement global ((Actifs - Capitaux propres)/ Actifs) <1
    switch(true){
      case val<0.1:
        return 1;
      case val<=0.2:
        return 2;
      case val<=0.4:
        return 3
      case val<=0.45:
        return 4
      case val<=0.49:
        return 5
      case val<=0.6:
        return 6
      case val<=0.7:
        return 7
      case val<=0.8:
        return 8
      case val<=1:
        return 9
      default:
        return 10
    }
  }

  static getNote20(val){ //Capacité de  Remboursement (DLMT/CAFG)
    switch(true){
      case val<1:
        return 1;
      case val<1.5:
        return 2;
      case val<2:
        return 3
      case val<3:
        return 4
      case val<3.4:
        return 5
      case val<3.8:
        return 6
      case val<4:
        return 7
      case val<4.4:
        return 8
      case val<5:
        return 9
      default:
        return 10
    }
  }

  static getNote21(val){ //Couverture des frais financiers (FTAO/FF)
    switch(true){
      case val>6:
        return 1;
      case val>5:
        return 2;
      case val>4:
        return 3
      case val>3:
        return 4
      case val==3:
        return 5
      case val<3: /* revoir a partir d'ici */
        return 6
      case val<2:
        return 7
      case val<1.5:
        return 8
      case val<1:
        return 9
      default:
        return 10
    }
  }

  static getNote22(val){ //***Solvabilité globale  Risque liquidatif (Ressources Propres /Total Bilan)
    switch(true){
      case val<3:
        return 10;
      case val<5:
        return 9;
      case val<10:
        return 8
      case val<15:
        return 7
      case val<20:
        return 6
      case val<25:
        return 5
      case val==25:
        return 4
      case val>35:
        return 1
      case val>30:
        return 2
      case val>25:
        return 3
      default:
        return 10
    }
  }

  static getNote23(val){ //Liquidité générale (Ratio de FR= Actifs circulants/ Passifs circulants)>1
    switch(true){
      case val<=0.2:
        return 10;
      case val<=0.5:
        return 9;
      case val<=0.8:
        return 8
      case val<=1:
        return 7
      case val<=1.2:
        return 6
      case val<=1.4:
        return 5
      case val<=1.6:
        return 4
      case val<=1.8:
        return 3
      case val<=2:
        return 2
      default:
        return 1
    }
  }

  static getNote24(val){ //Rentabilité d'exploitation par rapport au CA (EBE/ CA)>30%
    switch(true){
      case val<10:
        return 10;
      case val<15:
        return 9;
      case val<25:
        return 8
      case val<30:
        return 7
      case val==30: /** Attention a partir d'ici */
        return 6

      case val>50:
        return 1
      case val>45:
        return 2
      case val>40:
        return 3
      case val>35:
        return 4
      case val>30:
        return 5
      default:
        return 1
    }
  }

  static getNote25(val){ //Rentabilité économique ( REX/ACTIF)>10%
    switch(true){
      case val<1:
        return 10;
      case val<3:
        return 9;
      case val<5:
        return 8
      case val<7.5:
        return 7
      case val>10:
        return 6
      case val == 10: /** Attention */
        return 5

      case val>20:
        return 1
      case val>17.5:
        return 2
      case val>15:
        return 3
      case val>12.5:
        return 4
      default:
        return 1
    }
  }

  static getNote26(val){ //Rentabilité Financière (RN/Capitaux propres)>10%
    switch(true){
      case val<1:
        return 10;
      case val<3:
        return 9;
      case val<5:
        return 8
      case val<7.5:
        return 7
      case val<10:
        return 6
      case val==10: /** Attention a partir d'ici */
        return 5

      case val>20:
        return 1
      case val>17.5:
        return 2
      case val>15:
        return 3
      case val>12.5:
        return 4
      default:
        return 1
    }
  }

  static getNote27(val){ //Capacité d'endettement (Ressources Propres/Dettes structurelles)>1
    switch(true){
      case val<0.2:
        return 10;
      case val<0.5:
        return 9;
      case val<0.8:
        return 8
      case val<1:
        return 7
      case val==1: /** Attention a partir d'ici */
        return 6

      case val>2:
        return 1
      case val>1.8:
        return 2
      case val>1.6:
        return 3
      case val>1.4:
        return 4
      case val>1.2:
        return 5
      default:
        return 1
    }
  }

  static getNote28(val){ //Délai Client   ( (créances client /CA)*365)
    switch(true){
      case val<10:
        return 1;
      case val<20:
        return 2;
      case val<30:
        return 3
      case val<40:
        return 4
      case val<50:
        return 5
      case val<60:
        return 6
      case val<70:
        return 7
      case val<80:
        return 8
      case val<90:
        return 9
      default:
        return 10
    }
  }

  static getNote29(val){ //Délai Fournisseur ( (dettes fournisseurs /Achats à crédit)*365)
    switch(true){
      case val<10:
        return 10;
      case val<=20:
        return 9;
      case val<=30:
        return 8
      case val<=40:
        return 7
      case val<=50:
        return 6
      case val<=60:
        return 5
      case val<=70:
        return 4
      case val<=80:
        return 3
      case val<=90:
        return 2
      default:
        return 10
    }
  }

  static getNote30(val){ //Délai d'Ecoulement des stocks  ( (stock moyen/Coûts des produits vendus)*365 )
    switch(true){
      case val<20:
        return 1;
      case val<40:
        return 2;
      case val<60:
        return 3
      case val<80:
        return 4
      case val<100:
        return 5
      case val<120:
        return 6
      case val<140:
        return 7
      case val<160:
        return 8
      case val<180:
        return 9
      default:
        return 10
    }
  }


}
