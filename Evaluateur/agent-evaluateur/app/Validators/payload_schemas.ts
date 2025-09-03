import { z } from 'zod'

export const valueSchema = z.union([
  z.object({ type: z.literal('number'), value: z.number() }),
  z.object({ type: z.literal('enum'), value: z.string() }),
  z.object({ type: z.literal('text'), value: z.string() }),
])

export const subSchema = z.object({
  key: z.string(),
  label: z.string().optional(),
  value: valueSchema,
})

export const catSchema = z.object({
  key: z.string(),
  label: z.string().optional(),
  items: z.array(subSchema).nonempty(),
})

export const gridSchema = z.object({
  gridId: z.string(),
  metadata: z.record(z.string(), z.unknown()).optional(),
  categories: z.array(catSchema).nonempty(),
})
