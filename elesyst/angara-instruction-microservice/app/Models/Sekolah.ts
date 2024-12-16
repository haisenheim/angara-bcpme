import { DateTime } from 'node_modules/@types/luxon'
import { BaseModel, column } from '@ioc:Adonis/Lucid/Orm'

export default class Sekolah extends BaseModel {
  @column({ isPrimary: true })
  public id: number

  @column()
  public nama_sekolah

  @column()
  public kode_sekolah

  @column.dateTime({ autoCreate: true })
  public createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  public updatedAt: DateTime
}
