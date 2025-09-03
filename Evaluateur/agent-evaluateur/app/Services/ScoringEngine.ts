import { weights, scales, riskClasses } from '../../config/rules'

type ScoreDetail = {
  key: string
  rawValue: any
  score: number     // 1..10 (bas = bon)
  weight: number
  weighted: number  // weight * score
  interpretation?: string
}

type CategoryResult = {
  key: string
  items: ScoreDetail[]
  sumWeighted: number
}

export class ScoringEngine {
  static scoreValue(key: keyof typeof weights, raw: any) {
    const rule = (scales as any)[key]
    if (!rule) return { score: 5 as number, interpretation: undefined }

    if (rule.type === 'enum') {
      const s = rule.mapping?.[raw] ?? 5
      const explanation = rule.explain?.[s]
      return { score: s, interpretation: explanation }
    }

    if (rule.type === 'number') {
      const v = Number(raw)
      for (const bin of rule.bins) {
        if (v <= bin.maxExclusive) return { score: bin.score, interpretation: bin.text }
      }
      return { score: 5, interpretation: undefined }
    }

    return { score: 5, interpretation: undefined }
  }

  static evaluate(grid: any) {
    const categories: CategoryResult[] = []
    let total = 0

    for (const cat of grid.categories) {
      const items: ScoreDetail[] = []
      let sum = 0

      for (const it of cat.items) {
        const key = it.key as keyof typeof weights
        const w = weights[key] ?? 0
        const raw = it.value?.value ?? it.value
        const { score, interpretation } = this.scoreValue(key, raw)
        const weighted = w * score
        items.push({ key: String(key), rawValue: raw, score, weight: w, weighted, interpretation })
        sum += weighted
      }
      categories.push({ key: cat.key, items, sumWeighted: sum })
      total += sum
    }

    // tri forces/faiblesses par impact pondéré
    const all = categories.flatMap((c) => c.items)
    const weaknesses = [...all].sort((a,b)=> b.weighted - a.weighted).slice(0,5)
    const strengths  = [...all].sort((a,b)=> a.weighted - b.weighted).slice(0,5)

    const clazz = riskClasses.find((c)=> total <= c.max) ?? riskClasses[riskClasses.length - 1]
    return { total, class: clazz, categories, strengths, weaknesses }
  }
}
