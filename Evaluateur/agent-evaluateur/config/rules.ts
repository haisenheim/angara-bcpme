// config/rules.ts
export const weights = {
  // 1. Stratégie & gestion
  statut_foncier: 0.02,
  score_esg: 0.04,
  age_cacaoyers: 0.04,
  org_collective: 0.04,
  encadrement_tech: 0.04,
  acces_intrants: 0.03,
  diversification_revenus: 0.04,

  // 2. Production
  densite_plants_ha: 0.06,
  rendement_kg_ha: 0.06,
  production_totale_kg_an: 0.05,
  volatilite_prix: 0.04,
  ca_sous_contrat: 0.04,

  // 3. Charges
  mo_entretien_recolte: 0.02,
  intrants_montant: 0.015,
  materiel: 0.005,
  transport_collecte: 0.005,
  loyer_cotisations_divers: 0.005,

  // 4. Résultat d’exploitation
  resultat_net_previsionnel: 0.10,

  // 5. Investissements & BFR
  invest_sechoir: 0.02,
  invest_pulverisateur: 0.02,
  invest_replantation_partielle: 0.02,
  invest_compost: 0.02,
  bfr_tresorerie_campagne: 0.02,

  // 6. Capacité de remboursement
  taux_couverture_rn_annu: 0.10,

  // 7. Garanties
  garantie_morale: 0.01,
  histo_credit: 0.01,
  garantie_physique: 0.01,
  garantie_autres: 0.02,

  // 8. Risques
  risque_maladies: 0.06,
  risque_sechage: 0.03,
  risque_collecte: 0.01,
} as const

// Extraits réels + TODO pour compléter depuis Excel (« VALEURS DES SOUS-CRITERES »)
export const scales = {
  // ENUMS
  statut_foncier: {
    type: 'enum',
    mapping: {
      'Propriétaire / Dirigeant': 1,
      'Propriétaire Non-dirigeant': 2,
      'Héritier / Professionnel': 3,
      'Héritier Non-professionnel': 4,
      'Location': 5,
      'Usufruitier / Professionnel': 6,
      'Usufruitier Non-professionnel': 7,
      'NS': 8,
      '-': 9,
      'Autres': 10,
    },
    explain: {
      1: 'Sécurité foncière maximale',
      5: 'Sécurité foncière moyenne (location)',
      10:'Sécurité foncière très faible'
    }
  },

  // NUMERIQUES (bins) — + la valeur est basse, + l’appréciation est bonne
  densite_plants_ha: {
    type: 'number',
    bins: [
      { maxExclusive: Infinity, score: 1, text: '>1200' },
      { maxExclusive: 1200, score: 2, text: ']1200;1000]' },
      { maxExclusive: 1000, score: 3, text: ']1000;800]' },
      { maxExclusive: 800,  score: 4, text: ']800;700]' },
      { maxExclusive: 700,  score: 5, text: ']700;600]' },
      { maxExclusive: 600,  score: 6, text: ']600;500]' },
      { maxExclusive: 500,  score: 7, text: ']500;400]' },
      { maxExclusive: 400,  score: 8, text: ']400;300]' },
      { maxExclusive: 300,  score: 9, text: ']300;200]' },
      { maxExclusive: 200,  score: 10, text: '<200' },
    ],
  },
  rendement_kg_ha: {
    type: 'number',
    bins: [
      { maxExclusive: Infinity, score: 1, text: '>1000' },
      { maxExclusive: 1000, score: 2, text: ']1000;900]' },
      { maxExclusive: 900,  score: 3, text: ']900;800]' },
      { maxExclusive: 800,  score: 4, text: ']800;700]' },
      { maxExclusive: 700,  score: 5, text: ']700;600]' },
      { maxExclusive: 600,  score: 6, text: ']600;500]' },
      { maxExclusive: 500,  score: 7, text: ']500;400]' },
      { maxExclusive: 400,  score: 8, text: ']400;300]' },
      { maxExclusive: 300,  score: 9, text: ']300;200]' },
      { maxExclusive: 200,  score: 10, text: '<200' },
    ],
  },

  // Risques (enum)
  risque_maladies: {
    type: 'enum',
    mapping: {
      'Très modérés': 1, 'Modérés': 2, 'Plutôt faibles': 3, 'Plutôt sensibles': 4,
      'Assez sensibles': 5, 'Sensibles': 6, 'Assez forts': 7, 'Forts': 8,
      'Très forts': 9, 'Extrêmement Forts': 10
    }
  },

  // TODO: compléter pour score_esg, age_cacaoyers, org_collective, encadrement_tech,
  // acces_intrants, diversification_revenus, volatilite_prix, ca_sous_contrat,
  // charges détaillées, garanties, etc.
} as const

export const riskClasses = [
  { max: 1.5, code: 'SME2', label: 'Très bon' },
  { max: 2.5, code: 'SME3', label: 'Bon' },
  { max: 3.5, code: 'SME4', label: 'Assez bon' },
  { max: 4.5, code: 'SME5', label: 'Acceptable' },
  { max: 6.0, code: 'SME6', label: 'Fragile' },
  { max: 7.5, code: 'SME7', label: 'Risque élevé' },
  { max: 10,  code: 'SME8', label: 'Risque très élevé' },
]
