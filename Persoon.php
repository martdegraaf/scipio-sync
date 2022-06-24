<?php
/**
 * Een representatie van een persoon uit Scipio, met een wijk en een emailadres.
 */
class Persoon {
	public $status;
	public $email;
	public $wijk;

	public function has_email(){
		return is_string($this->email) && $this->email != "";
	}

	public function get_wijk(){
		if(!is_string($this->wijk) || $this->wijk == ""){
			return "nietingedeeld";
		}
		return strtolower(str_replace(' ', '', $this->wijk));
	}

	public function get_email(){
		return trim(strtolower(str_replace(' ', '', $this->email)));
	}

	public function is_actief(){
		return $this->status == "actief";
	}

	public function should_export_email(){
		return $this->has_email() &&
			$this->is_actief();
	}

	public static function to_object(array $array){
		$object = new Persoon();

		foreach ($array as $key => $value)
		{
			// Add the value to the object
			$object->{$key} = $value;
		}
		return $object;
	}
}