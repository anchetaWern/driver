<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Googl;

class HomeController extends Controller
{
   	public function index()
   	{
    	return view('login');
   	}


   	public function login(Googl $googl, Request $request)
   	{
		$client = $googl->client();

		if ($request->has('code')) {

			$client->authenticate($request->input('code'));
			$token = $client->getAccessToken();

			$plus = new \Google_Service_Plus($client);

			$google_user = $plus->people->get('me');
			$id = $google_user['id'];

			$email = $google_user['emails'][0]['value'];
			$first_name = $google_user['name']['givenName'];
			$last_name = $google_user['name']['familyName'];

			session([
				'user' => [
					'email' => $email,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'token' => $token
				]
			]);

			return redirect('/dashboard')
				->with('message', ['type' => 'success', 'text' => 'You are now logged in.']);

		} else {
			$auth_url = $client->createAuthUrl();
		    return redirect($auth_url);
		}
   }
}
