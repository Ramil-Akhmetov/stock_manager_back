<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Администратор') ? true : null;
        });

        //TODO check verifing email, maybe should hold it in fronted instead
        // VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        //     // $url for frontend
        //     $url = env('FRONTEND_URL', 'http://localhost:5173') . '/email-verify/' . $url;

        //     return (new MailMessage)
        //         ->subject('Verify Email Address')
        //         ->line('Click the button below to verify your email address.')
        //         ->action('Verify Email Address', $url);
        // });

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $url = env('FRONTEND_URL', 'http://localhost:5173') . '/reset-password/' . $token;
            return $url;
        });
    }
}
