<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Socialite;
use App\SocialProfile;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    public function redirectToSocialNetwork($socialNetwork)
    {
        return Socialite::driver($socialNetwork)->redirect();
    }

    public function handleSocialNetworkCallback($socialNetwork)
    {
        try {
            $socialUser = Socialite::driver($socialNetwork)->user();
        } catch (\Throwable $th) {
            return redirect()->route('login')->withWarning('Hubo un error en el login ...');
        }

        // Verificamos que existe un identificador de usuario de la red social
        $socialProfile = SocialProfile::firstOrNew([
            'social_network' => $socialNetwork,
            'social_network_user_id' => $socialUser->getId()
        ]);

        if (! $socialProfile->exists )
        {
            // Verificar que existe un usuario con el email de la red social
            $user = User::firstOrNew(['email' => $socialUser->getEmail()]);

            if (!$user->exists)
            {
                $user->name = $socialUser->getName();
                $user->save();
            }

            $socialProfile->avatar = $socialUser->getAvatar();

            $user->profiles()->save($socialProfile);
        }

        Auth::login($socialProfile->user);

        return redirect()->route('home')->withSuccess("Bienvenido {$socialProfile->user->name}");
    }
}
