<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SosialAccountMitra as SosialAccountMitra;
use App\Models\SosialAccountCustomer as SosialAccountCustomer;
use App\Models\Tbl_user_mitra as UserMitra;
use App\Models\Tbl_customer as UserCustomer;
use Session;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider, $guest)
    {
        
		Session::put('guest', $guest);
		
		return Socialite::driver($provider)->redirect();
    }
    public function hadleProviderCallback($provider)
    {
        $guest = Session::pull('guest');
		
			
		try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            $user = Socialite::driver($provider)->stateless()->user();
        }
			Auth::logout();
			 
			if($guest == 'customer'){
				
				$authUser = $this->findOrCreateUserCustomer($user, $provider);
				 
				Auth::login($authUser, true);
				$token = $authUser->createToken('auth_token', ['customer'])->plainTextToken;
				$response = [
					'name' => $user->name,
					'message' => 'Your request has been saved',
				];
				return redirect()->intended('http://localhost/bidme/public/set_cookie?token=' . $token)->with('token', $token);
				
			}elseif($guest == 'mitra'){
				$authUser = $this->findOrCreateUserMitra($user, $provider);
				
				Auth::login($authUser, true);
				$token = $authUser->createToken('auth_token', ['mitra'])->plainTextToken;
				$response = [
					'name' => $user->name,
					'message' => 'Your request has been saved',
				];
				
				return redirect()->intended('http://localhost/mitraBidme/public/set_cookie?token=' . $token)->with('token', $token);
			}else{
				return response()->json(401);
			};
    }

    public function findOrCreateUserCustomer($userProvider, $provider)
    {
        $account = SosialAccountCustomer::where(['provider_name' => $provider, 'provider_id' => $userProvider->id])->first();
        if ($account) {
			 
            return $account->user;
        } else {
            $user = UserCustomer::where('email', $userProvider->email)->first();
            if (!$user) {
                $user = UserCustomer::create([

                    'email' => $userProvider->email,

                    'name'  => $userProvider->name,
					
                    'avatar'  => $userProvider->avatar,
                ]);
            }
            $user->SosialAccountCustomer()->create([
                'provider_id'   => $userProvider->id,
                'provider_name' => $provider,
            ]);
            return $user;
        }
    }
	
	public function findOrCreateUserMitra($userProvider, $provider)
    {
        $account = SosialAccountMitra::where(['provider_name' => $provider, 'provider_id' => $userProvider->id])->first();
        if ($account) {
			 
            return $account->user;
        } else {
            $user = UserMitra::where('email', $userProvider->email)->first();
            if (!$user) {
                $user = UserMitra::create([

                    'email' => $userProvider->email,

                    'name'  => $userProvider->name,
					
                    'avatar'  => $userProvider->avatar,
                ]);
            }
            $user->SosialAccountMitra()->create([
                'provider_id'   => $userProvider->id,
                'provider_name' => $provider,
            ]);
            return $user;
        }
    }
}
