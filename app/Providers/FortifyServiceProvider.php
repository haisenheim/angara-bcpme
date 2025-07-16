<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Agence;
use App\Models\Programme;
use App\Models\Representation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Session;

class FortifyServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });
     /*   $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = auth()->user();
                dd(auth()->user());
                //$this->connect();

                //return redirect()->intended('/'.$request->alias.'/admin/dashboard');
            }

            private function connect(){
                    $user = auth()->user();
                    dd(auth()->user());
                if(tenant()){
                        $role_id = $user->role_id;
                        if($role_id == 1){
                            return redirect('/cooperative/dashboard');
                        }
                        return auth()->logout();
                }else{
                    if($user){
                        $ux = User::find($user->id);
                        Session::put('user',$ux);
                        $role_id = $user->role_id;
                        if($role_id == 1){
                            return redirect('/admin/dashboard');
                        }
                        if($role_id == 2){
                            return redirect('/pca/dashboard');
                        }
                        if($role_id == 3){
                                return redirect('/adm/dashboard');
                            }
                        if($role_id == 4){
                                return redirect('/dg/dashboard');
                            }
                        if($role_id == 5){
                                return redirect('/dga/dashboard');
                            }
                        if($role_id == 6){
                                return redirect('/respexp/dashboard');
                            }
                        if($role_id == 7){
                                return redirect('/respaud/dashboard');
                            }
                        if($role_id == 8){
                                return redirect('/respci/dashboard');
                            }
                        if($role_id == 9){
                                return redirect('/reri/dashboard');
                            }
                        if($role_id == 10){
                                return redirect('/reju/dashboard');
                            }
                        if($role_id == 11){
                                $region = Representation::find($user->representation_id);
                                Session::put('region',$region);
                                return redirect('/regional/dashboard');
                        }
                        if($role_id == 12){
                            $agence = Agence::find($user->agence_id);
                            Session::put('agence',$agence);
                            return redirect('/ca/dashboard');
                        }
                        if($role_id == 13){
                            $agence = Agence::find($user->agence_id);
                            Session::put('agence',$agence);
                            return redirect('/gestionnaire/dashboard');
                        }
                        if($role_id == 14){
                            $agence = Agence::find($user->agence_id);
                            Session::put('agence',$agence);
                            return redirect('/analyste/dashboard');
                        }
                        if($role_id == 19){
                            return redirect('/program/dashboard');
                        }
                        if($role_id == 21){
                            return redirect('/cooperative/dashboard');
                        }
                        return redirect('/login');
                        }
                }

            }
        }); */
    }

    protected function isSubdomain(string $hostname): bool
    {
        $central = config('tenancy.central_domains')[0];
        if($hostname == $central){
            return false;
        }
        return Str::endsWith($hostname, $central);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Fortify::ignoreRoutes();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function (Request $request) {
            $host = $request->getHost();
            if($this->isSubdomain($host)){
                return view('Tenant.Auth.login');
            }
            return view('Auth.login');
        });
    }
}
