<?php

require $_SERVER['DOCUMENT_ROOT'] . '/app/Net/SSH2.php';
require $_SERVER['DOCUMENT_ROOT'] . '/app/Crypt/RSA.php';

class Connect{
	public function __construct()
	{
		if (isset($_POST['HOSTNAME'])) {
			$ssh = new Net_SSH2($_POST['HOSTNAME']);
			if (!$ssh->login('???', '???')) {
				exit('Login to failed.');
			}
			echo $ssh->exec('pwd');
		}
	}
}

$connect = new Connect();
