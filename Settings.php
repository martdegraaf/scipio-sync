<?php
/**
 * The settings class responsible for loading settings.json and giving the correct settings to the classes.
 */
class Settings{
	private object $settings;
	
	function __construct($name) {
		$this->GetSettings();
	}

	public function GetSettings(){
		$settingsContents = file_get_contents("settings.json");
		$this->settings = json_decode($settingsContents);
	}
	
	public function GetBlacklist(){
		return $settings->blacklist;
	}

	public function GetScipioSettings(){
		return $settings->scipio;
	}

	public function GetEmailDomain(){
		return $settings->emailDomain;
	}
}