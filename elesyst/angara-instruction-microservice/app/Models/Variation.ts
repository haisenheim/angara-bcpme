import { DateTime } from 'node_modules/@types/luxon'
import { BaseModel, column } from '@ioc:Adonis/Lucid/Orm'

export default class Variation extends BaseModel {
  @column({ isPrimary: true })
  public id: number

  @column()
  public n_0

  @column()
  public n_1

  @column.dateTime({ autoCreate: true })
  public createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  public updatedAt: DateTime


}
