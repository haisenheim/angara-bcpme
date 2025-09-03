//import type { HttpContext } from '@adonisjs/core/http'
import { HttpContext } from '@adonisjs/core/build/standalone'
//import Env from '@adonisjs/core/services/env'
//import env from '@adonisjs/env'
import { createHmac } from 'node:crypto'

export default class AuthHmac {
  async handle({ request, response }: HttpContext, next: () => Promise<void>) {
    const signature = request.header('X-Signature')
    const timestamp = request.header('X-Timestamp')
    if (!signature || !timestamp) return response.unauthorized({ error: 'Missing signature' })

    //const secret = env.('HMAC_SECRET')
    const secret = process.env.HMAC_SECRET
    if (!secret) return response.unauthorized({ error: 'Missing HMAC secret' })
    const body = JSON.stringify(request.body() ?? {})
    const base = `${timestamp}.${body}`
    const expected = createHmac('sha256', secret).update(base).digest('hex')
    if (expected !== signature) return response.unauthorized({ error: 'Bad signature' })

    await next()
  }
}
