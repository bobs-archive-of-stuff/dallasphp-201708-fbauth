<?php

namespace Routes\Common;

use \App    as App;
use \Routes as Routes;
use \Nether as Nether;

class PublicWeb {

	public function
	__construct() {

		$this->User = NULL;
		$this->Router = Nether\Stash::Get('Router');

		if(array_key_exists('UserID',$_SESSION) && $_SESSION['UserID'])
		$this->User = App\User::GetByID((Int)$_SESSION['UserID']);

		$this->OnConstruct();
		return;
	}

	protected function
	OnConstruct():
	Void {

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	GetURL(String $Path=NULL, String $Query=NULL):
	String {
	/*//
	generate a url based on the current domain either from the
	current request or with the specified paths and queries.
	//*/

		if($Path === NULL)
		$Path = (($this->Router->GetPath() === '/index')?
			('/'):
			($this->Router->GetPath())
		);

		if($Query === NULL)
		$Query = ((count($this->Router->GetQuery()) >= 1)?
			($this->Router->QueryCooker($this->Router->GetQuery())):
			('')
		);

		return sprintf(
			'%s://%s%s%s',
			$this->Router->GetProtocol(),
			$this->Router->GetFullDomain(),
			$Path,
			$Query
		);
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Goto(String $URL):
	Void {
	/*//
	send the location redirection header.
	//*/

		header("Location: {$URL}");
		exit(0);
		return;
	}

}
