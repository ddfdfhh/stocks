<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /****Login responsese After setting session  */
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
              
                return response()->json(['message'=>'Logged successfully','url'=>route('admin.dashboard'),'success'=>true],200);
               
            }
        });
        $this->app->instance(RegisterResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
               return response()->json(['message'=>'Registered successfully','success'=>true,'url'=>route('login')],200);
              
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
       
        Fortify::loginView(function () {
            return view('auth.login');
        });
        Fortify::registerView(function () {
            return view('auth.register');
        });
        // ResetPassword::createUrlUsing(function ($user, string $token) {
        //     return 'https://example.com/reset-password?token='.$token;
        // });
        Fortify::resetPasswordView(function ($request) {
          //  dd($request->token);
            return view('auth.reset-password', ['request' => $request]);
        });
        Fortify::requestPasswordResetLinkView(function () {
           
            return view('auth.forgot-password');
        });
        // ResetPassword::toMailUsing(function($user, string $token) {
        //     $url=sprintf('%s/password_reset/%s', config('app.url'), $token);
        //         return (new MailMessage)
        //             ->subject('Reset Password')
        //             ->greeting('Hello!')
        //             ->line('You requested for reset password')
        //             ->action('Reset Password Link', $url);
                   
                   
        //     });
            VerifyEmail::toMailUsing(function ($notifiable) {
                $verifyUrl = $this->verificationUrl($notifiable);
    
                // Return your mail here...
                return (new MailMessage)
                    ->subject('Verify your email address')
                    ->markdown('emails.verify', ['url' => $verifyUrl]);
            });
    }
}
