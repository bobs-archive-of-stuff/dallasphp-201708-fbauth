<?php

namespace Routes;

use \App      as App;
use \Facebook as FB;
use \Routes   as Routes;
use \Nether   as Nether;

use \Throwable as Throwable;

class Auth
extends Routes\Common\PublicWeb {

	protected static function
	GetFacebookAPIs():
	Array {

		$Facebook = new FB\Facebook(Nether\Option::Get('FacebookAPI'));

		return [
			$Facebook,
			$Facebook->GetRedirectLoginHelper()
		];
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Logout():
	Void {

		unset($_SESSION['UserID']);

		$this->Goto('/');
		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	FacebookInit():
	Void {
	/*//
	kicks off the facebook authentication train. just send a link
	here to get it going from anywhere.
	//*/

		// get api
		list($Facebook,$Helper) = static::GetFacebookAPIs();

		// generate options
		$CallbackURL = $this->GetURL('/auth/fb-confirm');
		$Permissions = [ 'email' ];

		// determine facebook login url
		$FacebookURL = $Helper->GetLoginURL(
			$CallbackURL,
			$Permissions
		);

		// send user there
		$this->Goto($FacebookURL);

		return;
	}

	public function
	FacebookConfirm():
	Void {
	/*//
	handle the return trip from the facebook auth train.
	determine if they sent us a valid idenity or not.
	//*/

		list($Facebook,$Helper) = static::GetFacebookAPIs();
		$Token = NULL;
		$About = NULL;
		$Error = NULL;
		$User  = NULL;

		// check if we got an auth token from fb.

		try { $Token = $Helper->GetAccessToken(); }

		catch(Throwable $Error) {
			echo "TOKEN INIT: {$Error->GetMessage()}";
			return;
		}

		if($Token === NULL) {
			echo "TOKEN INIT: No auth attempt";
			return;
		}

		// try to upgrade token to longer lasting.

		try {
			$Token = $Facebook
			->GetOAuth2Client()
			->GetLongLivedAccessToken($Token->GetValue());

			$Facebook
			->SetDefaultAccessToken($Token->GetValue());
		}

		catch(Throwable $Error) {
			echo "TOKEN UPGRADE: {$Error->GetMessage()}";
			return;
		}

		if($Token === NULL) {
			echo "TOKEN INIT: No auth attempt";
			return;
		}

		// try and read data we need about user.

		try {
			$About = $Facebook
			->Get('/me?fields=id,name,email,first_name,last_name')
			->GetGraphUser();
		}

		catch(Throwable $Error) {
			echo "INFO QUERY: {$Error->GetMessage()}";
			return;
		}

		$Info = [
			'FBID'  => $About->GetID(),
			'Name'  => $About->GetName(),
			'Email' => $About->GetEmail(),
			'Token' => $Token->GetValue()
		];

		if(!$Info['Email']) {
			echo "DATA: No email address specified. Please allow Facebook to share it.";
			return;
		}

		// see if we have a valid user.

		$User = App\User::GetByEmail($Info['Email']);

		if(!$User) {
			try { $User = App\User::Create($Info); }
			catch(Throwable $Error) {
				echo "DATABASE: {$Error->GetMessage()}";
				return;
			}
		}

		$_SESSION['UserID'] = $User->ID;
		$User->UpdateToken($Token->GetValue());

		$this->Goto('/');
		return;
	}

}
