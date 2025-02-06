@extends('Layouts.cooperative')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Sorties de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouvelle Sortie en stock</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle sortie de stock</h5>
        <p class="lead">Saisie d'une nouvelle sortie de stock </p>
    </div>
@endsection

@section('content')
    <div class="card form-md">
        <div class="card-header">
            <h4 class="text-primary text-center">Saisie d'une sortie de stock</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('cooperative.sorties.store')}}">
                @csrf
                <div class="d-flex gap-2">
                    <div class="w-25">
                        <label>AGENT</label>
                        <select required name="agent_id" class="form-control">
                            <option value="">Selectionner un agent ...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id}}">{{$agent->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-50">
                        <label>MEMBRE</label>
                        <select required name="exploitant_id" class="form-control">
                            <option value="">Selectionner un membre ...</option>
                            @foreach($exploitants as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-30">
                        <label>ENTREPROT DE DEPART</label>
                        <select required name="entrepot_id" class="form-control">
                            <option value="">Selectionner l'entrepot de stockage ...</option>
                            @foreach($entrepots as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <div class="w-30">
                        <label>GAMME</label>
                        <select required name="gamme_id" class="form-control">
                            <option value="">Selectionner une gamme ...</option>
                            @foreach($gammes as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-30">
                        <label>QUANTITE EN TONNE</label>
                        <input type="number" placeholder="Saisir la quantite ici..." class="form-control" required name="quantity" />
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="pli-save fs-4 me-2"></i> ENREGISTRER</button>
                </div>
            </form>
        </div>
    </div>
@endsection





ErrorException: include(/Users/haisenheim/projects/angara/vendor/composer/../../app/Http/Resources/EntrepriseListResource.php): Failed to open stream: No such file or directory in file /Users/haisenheim/projects/angara/vendor/composer/ClassLoader.php on line 576

#0 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php(255): Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(2, 'include(/Users/...', '/Users/haisenhe...', 576)
#1 /Users/haisenheim/projects/angara/vendor/composer/ClassLoader.php(576): Illuminate\Foundation\Bootstrap\HandleExceptions->Illuminate\Foundation\Bootstrap\{closure}(2, 'include(/Users/...', '/Users/haisenhe...', 576)
#2 /Users/haisenheim/projects/angara/vendor/composer/ClassLoader.php(576): include('/Users/haisenhe...')
#3 /Users/haisenheim/projects/angara/vendor/composer/ClassLoader.php(427): Composer\Autoload\{closure}('/Users/haisenhe...')
#4 /Users/haisenheim/projects/angara/app/Http/Controllers/Gestionnaire/CompanyController.php(45): Composer\Autoload\ClassLoader->loadClass('App\\Http\\Resour...')
#5 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Controller.php(54): App\Http\Controllers\Gestionnaire\CompanyController->fetchAll()
#6 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php(43): Illuminate\Routing\Controller->callAction('fetchAll', Array)
#7 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Route.php(259): Illuminate\Routing\ControllerDispatcher->dispatch(Object(Illuminate\Routing\Route), Object(App\Http\Controllers\Gestionnaire\CompanyController), 'fetchAll')
#8 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Route.php(205): Illuminate\Routing\Route->runController()
#9 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(806): Illuminate\Routing\Route->run()
#10 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\Routing\Router->Illuminate\Routing\{closure}(Object(Illuminate\Http\Request))
#11 /Users/haisenheim/projects/angara/app/Http/Middleware/Gestionnaire.php(48): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#12 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): App\Http\Middleware\Gestionnaire->handle(Object(Illuminate\Http\Request), Object(Closure))
#13 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php(50): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#14 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Routing\Middleware\SubstituteBindings->handle(Object(Illuminate\Http\Request), Object(Closure))
#15 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php(57): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#16 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Auth\Middleware\Authenticate->handle(Object(Illuminate\Http\Request), Object(Closure))
#17 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php(78): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#18 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Foundation\Http\Middleware\VerifyCsrfToken->handle(Object(Illuminate\Http\Request), Object(Closure))
#19 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php(49): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#20 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\View\Middleware\ShareErrorsFromSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#21 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(121): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#22 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(64): Illuminate\Session\Middleware\StartSession->handleStatefulRequest(Object(Illuminate\Http\Request), Object(Illuminate\Session\Store), Object(Closure))
#23 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Session\Middleware\StartSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#24 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php(37): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#25 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse->handle(Object(Illuminate\Http\Request), Object(Closure))
#26 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php(67): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#27 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Cookie\Middleware\EncryptCookies->handle(Object(Illuminate\Http\Request), Object(Closure))
#28 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#29 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(805): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#30 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(784): Illuminate\Routing\Router->runRouteWithinStack(Object(Illuminate\Routing\Route), Object(Illuminate\Http\Request))
#31 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(748): Illuminate\Routing\Router->runRoute(Object(Illuminate\Http\Request), Object(Illuminate\Routing\Route))
#32 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(737): Illuminate\Routing\Router->dispatchToRoute(Object(Illuminate\Http\Request))
#33 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(200): Illuminate\Routing\Router->dispatch(Object(Illuminate\Http\Request))
#34 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\Foundation\Http\Kernel->Illuminate\Foundation\Http\{closure}(Object(Illuminate\Http\Request))
#35 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#36 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php(31): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#37 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull->handle(Object(Illuminate\Http\Request), Object(Closure))
#38 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#39 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php(40): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#40 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Foundation\Http\Middleware\TrimStrings->handle(Object(Illuminate\Http\Request), Object(Closure))
#41 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php(27): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#42 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Foundation\Http\Middleware\ValidatePostSize->handle(Object(Illuminate\Http\Request), Object(Closure))
#43 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php(99): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#44 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance->handle(Object(Illuminate\Http\Request), Object(Closure))
#45 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php(49): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#46 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Http\Middleware\HandleCors->handle(Object(Illuminate\Http\Request), Object(Closure))
#47 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php(39): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#48 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(183): Illuminate\Http\Middleware\TrustProxies->handle(Object(Illuminate\Http\Request), Object(Closure))
#49 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Http\Request))
#50 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(175): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#51 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(144): Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter(Object(Illuminate\Http\Request))
#52 /Users/haisenheim/projects/angara/public/index.php(51): Illuminate\Foundation\Http\Kernel->handle(Object(Illuminate\Http\Request))
#53 /Users/haisenheim/projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php(16): require_once('/Users/haisenhe...')
#54 {main}

