
import Route from '@ioc:Adonis/Core/Route'
// Make sure the path is correct and the file exists
//import EvaluatorController from 'App/Controllers/Http/EvaluatorController'
Route.get('/health', async () => ({ ok: true }))
Route.post('/evaluate', 'EvaluatorController.evaluate').middleware(['auth'])
Route.post('/evaluate/batch', 'EvaluatorController.batch').middleware(['auth'])
