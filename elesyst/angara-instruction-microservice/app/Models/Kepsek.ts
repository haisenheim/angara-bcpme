import { DateTime } from 'node_modules/@types/luxon'
import { BaseModel, column } from '@ioc:Adonis/Lucid/Orm'

export default class Kepsek extends BaseModel {
  @column({ isPrimary: true })
  public id: number

  @column()
  public nama_kepsek

  @column()
  public nip

  @column()
  public id_sekolah

  @column.dateTime({ autoCreate: true })
  public createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  public updatedAt: DateTime
}
