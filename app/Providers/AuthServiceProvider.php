<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
          if ($request->header('Authorization')) {
             $key = explode(' ',$request->header('Authorization'));
             $user = User::where(['api_key' => $key[0]])->first();
              if(!empty($user))
              {

                  //define authorize create_user
                  Gate::define('create_user', function($user)
                  {
                    //role_id = 1 adalah SuperAdmin (Allow), role_id = 2 adalah User (Deny)
                    if ($user->role_id == '1')
                      return true;
                    else
                      return false;
                  });

                  //define authorize create_event
                  Gate::define('create_event', function($user)
                  {
                    //role_id = 1 adalah SuperAdmin (Allow), role_id = 2 adalah User (Allow)
                    if ($user->role_id == '1')
                      return true;
                    else
                      return true;
                  });

                  //define authorize approve_event
                  Gate::define('approve_event', function($user)
                  {
                    //role_id = 1 adalah SuperAdmin (Allow), role_id = 2 adalah User (Deny)
                    if ($user->role_id == '1')
                      return true;
                    else
                      return false;
                  });


                  $request->request->add(['userid' => $user->id]);

              }

              return $user;
            }
        });
    }
}
