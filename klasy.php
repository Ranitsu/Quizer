<?php

class User
{
	function __construct($a, $b, $c, $d, $e, $f)
	{
		$this->id = $a;
		$this->login = $b;
		$this->haslo = $c;
		$this->email = $d;
		$this->nick = $e;
		$this->typkonta = $f;
	}
	
	function Wys_nick()
	{
		echo $this->nick;
	}
	
	function Return_typ()
	{
		return $this->typkonta;
	}
}

class Pytanie
{
	function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k)
	{
		$this->id = $a;
		$this->tresc = $b;
		$this->a = $d;
		$this->b = $e;
		$this->c = $f;
		$this->d = $g;
		$this->poprawna = $h;
		$this->id_kategorii = $i;
		$this->kategoria_nazwa = $i;
		$this->zalacznik = $j;
	}

}

class Kategoria
{
	function __construct($a, $b)
	{
		$this->id = $a;
		$this->nazwa = $b;
	}
}