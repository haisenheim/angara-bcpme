/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| This file is dedicated for defining HTTP routes. A single file is enough
| for majority of projects, however you can define routes in different
| files and just make sure to import them inside this file. For example
|
| Define routes in following two files
| ├── start/routes/cart.ts
| ├── start/routes/customer.ts
|
| and then import them inside `start/routes.ts` as follows
|
| import './routes/cart'
| import './routes/customer'
|
*/

import Route from '@ioc:Adonis/Core/Route'

Route.get('/', async () => {
  return { hello: 'world' }
})

// start/routes.ts
import { db } from 'App/Services/Db'
import { computeWeighted, scoreWithRules, parseNumeric } from 'App/Agent/scoring'
import { explainWithOpenAI } from 'App/Agent/agent'
import formidable from 'formidable'
import * as XLSX from 'xlsx'

type CritRow = { crit_key:string; crit_label:string; weight:number; value:any; score?:number|null; rules:any[] }

async function loadCriteriaFromDb(): Promise<Map<string,{label:string, weight:number}>> {
  const [rows] = await db.query('SELECT crit_key, crit_label, poids FROM criteria')
  const map = new Map<string,{label:string, weight:number}>()
  for (const r of rows as any[]) map.set(r.crit_key, {label:r.crit_label, weight:Number(r.poids)})
  return map
}

async function loadRulesByCrit(): Promise<Map<string, any[]>> {
  const [rows] = await db.query('SELECT * FROM criteria_rules')
  const map = new Map<string, any[]>()
  for (const r of rows as any[]) {
    const k = r.crit_key
    if (!map.has(k)) map.set(k, [])
    map.get(k)!.push({
      crit_key:k, rule_label:r.rule_label,
      min_value:r.min_value, max_value:r.max_value,
      include_min:r.include_min, include_max:r.include_max,
      categorical_value:r.categorical_value, score:r.score
    })
  }
  return map
}

// Extraction directe depuis un Excel envoyé (même format que votre Feuille 1)
function extractFromSheet1(sheet:any): CritRow[] {
  // On cherche la ligne "Critères de notation"
  const data = XLSX.utils.sheet_to_json(sheet, { header:1, raw:true })
  let hdr = data.findIndex((row:any[]) => String(row?.[0]||"").includes("Critères de notation"))
  if (hdr < 0) throw new Error("Entête 'Critères de notation' introuvable dans la Feuille 1.")
  const rows = data.slice(hdr+1)  // après l'entête
  const out: CritRow[] = []
  let currentSection = ""
  for (const r of rows) {
    const col0 = r?.[0]
    if (col0 == null) continue
    const label = String(col0).trim()
    if (/^\d+\.\s/.test(label)) { currentSection = label; continue }      // section
    if (["total","risques"].includes(label.toLowerCase())) continue

    const value = r?.[1]
    const weight = Number(r?.[2] || 0)
    const score  = (r?.[3] != null && r?.[3] !== "") ? Number(r[3]) : null
    const crit_key = label.toLowerCase()
      .normalize('NFKD').replace(/[^\w\s-]/g,'').replace(/\s+/g,'_').replace(/_+/g,'_').replace(/^_+|_+$/g,'')
    out.push({ crit_key, crit_label: label, weight, value, score, rules: [] })
  }
  return out
}

// POST /evaluate/from-excel (multipart form-data, field 'file', optional 'case_ref')
Route.post('/evaluate/from-excel', async ({ request, response }) => {
  const form = formidable({ multiples:false })
  const parsed = await new Promise<{fields:any, files:any}>((resolve,reject)=>{
    form.parse(request.request, (err, fields, files) => err ? reject(err) : resolve({fields, files}))
  })
  const case_ref = parsed.fields.case_ref?.toString() || `CASE_${Date.now()}`
  const file = parsed.files.file
  if (!file) return response.badRequest({ error:"file (xlsx) manquant" })

  const wb = XLSX.readFile(file.filepath)
  const sh1 = wb.Sheets[wb.SheetNames[0]]  // "GRILLE SCORING"
  const crits = extractFromSheet1(sh1)

  // joindre poids/labels depuis DB si voulu (optionnel – ici on garde ceux de l'Excel déjà présents)
  const rulesMap = await loadRulesByCrit()
  for (const c of crits) c.rules = rulesMap.get(c.crit_key) || []

  const scored = computeWeighted(crits.map(c => ({
    key:c.crit_key, label:c.crit_label, weight:c.weight, value:c.value, score:c.score, rules:c.rules
  })))

  const explained = await explainWithOpenAI(scored)

  // Persistance
  await db.query("INSERT INTO evaluations(case_ref, input_json, output_json) VALUES (?,?,?)", [
    case_ref,
    JSON.stringify({ source:"excel", crits }),
    JSON.stringify(explained)
  ])

  return response.ok(explained)
})

// POST /evaluate/from-db
// body: { case_ref: string, grid: [{crit_key, crit_label?, weight, value, score?}] }
// -> utile si vous stockez déjà la grille en MySQL (ou si vous envoyez directement le JSON)
Route.post('/evaluate/from-db', async ({ request, response }) => {
  const body = request.body()
  const case_ref = body.case_ref || `CASE_${Date.now()}`
  const grid = body.grid
  if (!Array.isArray(grid) || grid.length===0) return response.badRequest({ error:"grid manquante" })

  const rulesMap = await loadRulesByCrit()
  const rows = grid.map((g:any)=>({
    key: g.crit_key,
    label: g.crit_label || g.crit_key,
    weight: Number(g.weight||0),
    value: g.value,
    score: g.score ?? null,
    rules: rulesMap.get(g.crit_key) || []
  }))
  const scored = computeWeighted(rows)
  const explained = await explainWithOpenAI(scored)

  await db.query("INSERT INTO evaluations(case_ref, input_json, output_json) VALUES (?,?,?)", [
    case_ref, JSON.stringify({ source:"db", grid }), JSON.stringify(explained)
  ])

  return response.ok(explained)
})
