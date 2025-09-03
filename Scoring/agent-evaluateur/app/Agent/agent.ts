// app/Agent/agent.ts
import OpenAI from 'openai'
import { computeWeighted } from './scoring'

const client = new OpenAI({ apiKey: process.env.OPENAI_API_KEY })

const OutputSchema = {
  type: "json_schema",
  json_schema: {
    name: "FundingEvaluation",
    schema: {
      type: "object",
      required: ["decision","risk_bucket","global_score","per_criterion","strengths","weaknesses","rationale"],
      properties: {
        decision: { type:"string", enum:["ACCEPTER","AJOURNER","REFUSER"] },
        risk_bucket: { type:"string", enum:["faible","moyen","élevé"] },
        global_score: { type:"number" },
        per_criterion: {
          type:"array",
          items:{ type:"object", required:["key","score","weight"], properties:{
            key:{type:"string"}, label:{type:"string"}, score:{type:"number"}, weight:{type:"number"}, evidence:{type:"string"}
          }}
        },
        strengths:{ type:"array", items:{type:"string"} },
        weaknesses:{ type:"array", items:{type:"string"} },
        uncertainties:{ type:"array", items:{type:"string"} },
        similar_cases:{ type:"array", items:{type:"object"} },
        rationale:{ type:"string" }
      }
    }
  }
}

const SYSTEM = `
Tu es un agent d'évaluation de dossiers de financement cacao.
Tu reçois:
- une liste de critères avec (poids, score 1..10 s'il est déjà fourni, et/ou valeur brute + règles),
- un score global calculé (somme pondérée score/10 * poids).
Règles de décision par défaut (éditables côté serveur):
- global >= 0.75 => "faible"
- 0.5..0.75 => "moyen"
- < 0.5 => "élevé"
Si des éléments clés manquent, ajoute-les dans "uncertainties" et choisis "AJOURNER" si l'incertitude est bloquante.
Retourne STRICTEMENT le JSON du schéma.
`

export async function explainWithOpenAI(scoredJson: any) {
  const risk = scoredJson.global_score >= 0.75 ? "faible" : (scoredJson.global_score >= 0.5 ? "moyen" : "élevé")
  const decision = risk === "faible" ? "ACCEPTER" : (risk === "moyen" ? "AJOURNER" : "REFUSER")
  const input = [
      { role: "system", content: SYSTEM },
      { role: "user", content: [
        { type:"text", text: "Voici les éléments d'évaluation:" },
        { type:"text", text: JSON.stringify(scoredJson) },
        { type:"text", text: `Mon mapping automatique donne: risk_bucket="${risk}", decision="${decision}". Ajuste si nécessaire selon les évidences.` }
      ]}
    ]
  const resp = await client.responses.create({
    model: "gpt-5",
    input: [
      { role: "system", content: SYSTEM },
      { role: "user", content: [
        { type:"text", text: "Voici les éléments d'évaluation:" },
        { type:"text", text: JSON.stringify(scoredJson) },
        { type:"text", text: `Mon mapping automatique donne: risk_bucket="${risk}", decision="${decision}". Ajuste si nécessaire selon les évidences.` }
      ]}
    ],
    response_format: OutputSchema
  })



  // Pas d'outils nécessaires ici; retour direct du JSON conforme
  const out = JSON.parse(resp.output_text || "{}")
  // Forcer cohérence champ global_score
  out.global_score = scoredJson.global_score
  return out
}
