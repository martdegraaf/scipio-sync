<?php

/**
 * Class ScipioOnline uses scipio.wsdl to connect through Soap.
 * 
 */
class ScipioOnline
{
	private $conn;
	private $wsdl = 'scipio.wsdl';
	private $username = '';
	private $password = '';
	private $pincode = '';
	
	public $message = '';

	function __construct($scipioSettings) {
		$this->username = $scipioSettings->username;
		$this->password = $scipioSettings->password;
		$this->pincode = $scipioSettings->pincode;
	}

	public function call($function, $args) {
		if (!$this->conn) {
			$this->conn = new SoapClient($this->wsdl,['trace' => true]);
		}
		$res = $this->conn->$function($args);

		return $res;
	}

	public function GetLedenOverzicht() {
		$res = $this->call('GetLedenOverzicht', array(
			'Username' => $this->username,
			'Password' => $this->password,
			'Pincode'  => $this->pincode
		));

		$xml = $res->GetLedenOverzichtResult;

		$data = json_decode(json_encode((array) simplexml_load_string($xml)), 1);

		return $data;
	}
}