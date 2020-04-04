<?php

namespace App\Http\Middleware;

use Closure;
use Socialite;
use App\SocialProfile;

class RedirectIfSocialNetworkNotSupported
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $socialNetwork = $request->route('socialNetwork');

        if (collect(SocialProfile::$allowed)->contains($socialNetwork))
        {
            return Socialite::driver($socialNetwork)->redirect();
            return $next($request);

        }

        return redirect()->route('login')->withWarning("No es posible autenticarte con {$socialNetwork}.");
    }
}
