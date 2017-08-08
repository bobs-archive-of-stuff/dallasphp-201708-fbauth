<?php

namespace Routes;

use \App      as App;
use \Facebook as Facebook;
use \Nether   as Nether;
use \Routes   as Routes;

use \Throwable as Throwable;

class Home
extends Routes\Common\PublicWeb {

	public function
	Index() {

		if($this->User)
		$this->IndexLoggedIn();

		else
		$this->IndexLoggedOut();

		return;
	}

	public function
	About() {
		echo "About Page!";
		return;
	}

	public function
	NotFound() {
		header("404 Not Found");
		echo "404 - Not Found";
		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected function
	IndexLoggedIn():
	Void {
		echo "<h1>Welcome, {$this->User->Name}! <a href=\"/auth/logout\">Log Out</a></h1>";

		echo "<h3>About You</h3>";
		echo "<pre>";
		print_r($this->User);
		echo "</pre>";

		$this->PrintTokenInfo();
		$this->PrintUserList();
		return;
	}

	protected function
	IndexLoggedOut():
	Void {
		echo "<h1>Hello World! <a href=\"/auth/fb-init\">Log In</a></h1>";
		$this->PrintUserList();
		return;
	}

	protected function
	PrintUserList():
	Void {
		$Users = App\User::GetList();

		echo "<h3>User List</h3>";
		echo "<pre>";

		foreach($Users as $User)
		print_r($User);

		echo "</pre>";
		return;
	}

	protected function
	PrintTokenInfo():
	Void {

		echo "<h3>Token Info</h3>";

		try {
			$FB = new Facebook\Facebook(Nether\Option::Get('FacebookAPI'));
			$FB->SetDefaultAccessToken($this->User->Token);

			$TokenInfo = $FB
			->Get("/debug_token?input_token={$this->User->Token}")
			->GetGraphObject();
		}

		catch(Throwable $Error) {
			echo "<pre>Error Validating Token</pre>";
		}

		echo "<pre>";
		print_r($TokenInfo);
		echo "</pre>";

		return;
	}

}
