<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SosialAccount;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function hadleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            $user = Socialite::driver($provider)->stateless()->user();
        }
		
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        $token = $authUser->createToken('auth_token')->plainTextToken;
		$response = [
            'name' => $user->name,
            'message' => 'Your request has been saved',
        ];
         
        //return redirect()->intended(env('CLIENT_URL').'http:localhost/bidme/public/set_cookie?token=' . $token)->with('token', $token);
        return redirect()->intended('http://localhost/bidme/public/set_cookie?token=' . $token)->with('token', $token);
    }

    public function findOrCreateUser($userProvider, $provider)
    {
        $account = SosialAccount::where(['provider_name' => $provider, 'provider_id' => $userProvider->id])->first();
        if ($account) {
            return $account->user;
        } else {
            $user = User::where('email', $userProvider->email)->first();
            if (!$user) {
                $user = User::create([

                    'email' => $userProvider->email,

                    'name'  => $userProvider->name,
					
                    //'avatar'  => $userProvider->avatar,
                ]);
            }
            $user->SosialAccount()->create([
                'provider_id'   => $userProvider->id,
                'provider_name' => $provider,
            ]);
            return $user;
        }
    }
}
