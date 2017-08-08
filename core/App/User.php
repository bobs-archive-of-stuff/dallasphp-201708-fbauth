<?php

namespace App;

use \Nether as Nether;

class User
extends Nether\Object {

	public static
	$PropertyMap = [
		'ID'    => 'ID:int',
		'Name'  => 'Name',
		'Email' => 'Email',
		'token' => 'Token'
	];

	public function
	UpdateToken(String $Token):
	self {

		$this->Token = $Token;

		Nether\Database::Get()
		->NewVerse()
		->Update('Users')
		->Set([
			'Token' => ':Token'
		])
		->Where('ID=:ID')
		->Query($this);

		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public static function
	GetList():
	Array {

		$Output = [];

		$Result = Nether\Database::Get()
		->NewVerse()
		->Select('Users')
		->Fields('*')
		->Query();

		while($Row = $Result->Next())
		$Output[] = new static($Row);

		return $Output;
	}

	public static function
	GetByID(Int $ID):
	?self {

		$Row = Nether\Database::Get()
		->NewVerse()
		->Select('Users')
		->Fields('*')
		->Where('ID=:ID')
		->Limit(1)
		->Query([
			':ID' => $ID
		])
		->Next();

		if(!$Row)
		return NULL;

		return new static($Row);
	}

	public static function
	GetByEmail(String $Email):
	?self {

		$Row = Nether\Database::Get()
		->NewVerse()
		->Select('Users')
		->Fields('*')
		->Where('Email=:Email')
		->Limit(1)
		->Query([
			':Email' => $Email
		])
		->Next();

		if(!$Row)
		return NULL;

		return new static($Row);
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public static function
	Create($Opt=NULL):
	self {

		$Opt = new Nether\Object($Opt,[
			'Name'  => '',
			'Email' => '',
			'Token' => ''
		]);

		$Result = Nether\Database::Get()
		->NewVerse()
		->Insert('Users')
		->Fields([
			'Name'  => ':Name',
			'Email' => ':Email',
			'token' => ':Token'
		])
		->Query([
			':Name'  => $Opt->Name,
			':Email' => $Opt->Email,
			':Token' => $Opt->Token
		]);

		if(!$Result->IsOK())
		return NULL;

		return static::GetByID($Result->GetInsertID());
	}

}
