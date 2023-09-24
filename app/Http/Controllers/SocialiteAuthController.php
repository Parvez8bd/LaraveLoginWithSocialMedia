<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    //callback
    public function callback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();

        $user = User::where([
            'provider' => $provider,
            'provider_id' => $providerUser->id,
        ])->first();

        if (!$user) {
            $user = User::create( [
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'provider_token' => $providerUser->token,
                'provider_id' => $providerUser->id,
                'provider' => $provider,
            ]);
        }
        
        Auth::login($user);

        return redirect('/dashboard');
    }
}
