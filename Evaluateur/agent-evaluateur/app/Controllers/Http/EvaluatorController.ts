//import type { HttpContext } from '@adonisjs/core'
// Update the import path to the correct relative location, for example:
import { HttpContext } from '@adonisjs/core/build/standalone'
import { ScoringEngine } from '../../Services/ScoringEngine'
import { gridSchema } from '../../Validators/payload_schemas'

export default class EvaluatorController {
  async evaluate({ request, response }: HttpContext) {
    const payload = gridSchema.parse(request.body())
    const result = ScoringEngine.evaluate(payload)
    return response.ok({
      gridId: payload.gridId,
      total: Number(result.total.toFixed(3)),
      class: result.class,
      summary: {
        score10: Number(result.total.toFixed(3)),
        riskClass: result.class.code,
        riskLabel: result.class.label,
      },
      categories: result.categories,
      strengths: result.strengths,
      weaknesses: result.weaknesses,
    })
  }

  async batch({ request, response }: HttpContext) {
    const body = request.body()
    const list: any[] = Array.isArray(body) ? body : body?.grids
    if (!Array.isArray(list) || !list.length) return response.badRequest({ error: 'grids[] required' })

    const items = list.map((g) => {
      const payload = gridSchema.parse(g)
      const r = ScoringEngine.evaluate(payload)
      return {
        gridId: payload.gridId,
        score10: Number(r.total.toFixed(3)),
        riskClass: r.class.code,
        riskLabel: r.class.label,
        strengths: r.strengths,
        weaknesses: r.weaknesses,
      }
    })
    return response.ok({ count: items.length, items })
  }
}
