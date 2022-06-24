<?php
/**
 * The settings class responsible for loading settings.json and giving the correct settings to the classes.
 */
class Settings{
	private $settings;
	
	function __construct() {
		$this->GetSettings();
	}

	public function GetSettings(){
		$settingsContents = file_get_contents("config/settings.json");
		$this->settings = json_decode($settingsContents);
	}
	
	public function GetBlacklist(){
		return $this->settings->blacklist;
	}

	public function GetScipioSettings(){
		return $this->settings->scipio;
	}

	public function GetEmailDomain(){
		return $this->settings->emailDomain;
	}
}