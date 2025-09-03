// app/Agent/scoring.ts
import { RowDataPacket } from 'mysql2'

export type Criterion = { key:string; label:string; weight:number }
export type Rule = {
  crit_key:string; rule_label?:string; min_value?:number|null; max_value?:number|null;
  include_min?:number; include_max?:number; categorical_value?:string|null; score:number
}

export function parseNumeric(v:any): number | null {
  if (v == null) return null
  const s = String(v).replace(",", ".").trim()
  const n = Number(s)
  return Number.isFinite(n) ? n : null
}

export function scoreWithRules(value:any, rules:Rule[]): number | null {
  // Catégories exactes
  if (typeof value === 'string') {
    const cat = value.trim().toLowerCase()
    const r = rules.find(x => x.categorical_value && x.categorical_value.trim().toLowerCase() === cat)
    if (r) return r.score
  }
  // Numérique
  const num = parseNumeric(value)
  if (num != null) {
    for (const r of rules) {
      const lo = r.min_value ?? -Infinity
      const hi = r.max_value ?? +Infinity
      const ge = (r.include_min !== 0) ? (num >= lo) : (num > lo)
      const le = (r.include_max !== 0) ? (num <= hi) : (num < hi)
      if (ge && le) return r.score
    }
  }
  return null
}

export function computeWeighted(per: {key:string; label:string; weight:number; value:any; score?:number|null; rules:Rule[]}[]) {
  let sum = 0, wsum = 0
  const items: any[] = []
  for (const row of per) {
    let score = (row.score != null) ? row.score : scoreWithRules(row.value, row.rules)
    if (score == null) score = 0
    const part = (score/10) * row.weight
    sum += part; wsum += row.weight
    items.push({ key: row.key, label: row.label, score, weight: row.weight, evidence: String(row.value ?? "") })
  }
  return { global_score: (wsum>0?sum:0), per_criterion: items }
}
