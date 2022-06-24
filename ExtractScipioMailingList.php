<?php
/**
 * ExtractScipioMailingList is responsible for converting the members in scipio to a list of email forwards for the domain of the church.
 * The members will be devided by 'wijk' and everyone in a 'wijk' will get the mail. The structure is build so that 
 * noord@[DOMAIN]
 * zuid@[DOMAIN]
 * oost@[DOMAIN]
 * west@[DOMAIN] point to west1 and west2.. etc.
 * 
 * west1 then will point to the email adresses of the members.
 * 
 * The 'wijk' is set in Scipio.
 */
class ExtractScipioMailingList{
	const SUFFIX_FLAG = false;
	const WIJK_FLAG = true;

	private $scipio;
	private $settings;
	
	function __construct($scipioOnline, $settings) {
		$this->scipio = $scipioOnline;
		$this->settings = $settings;
	}

	public function extract(){
		$wijken = $this->GetWijkenFromScipio();
		ksort($wijken);
		var_export($wijken);
		$this->WriteWijkenToTextFile($wijken);
		return $this->WriteWijkenToJsonFile($wijken);
	}

	private function GetWijkenFromScipio(){
		$foundEmailAdresses = [];
		$wijken = [];
		$leden = $this->scipio->GetLedenOverzicht();
		foreach ($leden['persoon'] as $persoonArray) {
			$persoon = Persoon::to_object($persoonArray);
			
			if($persoon->should_export_email() && 
				!in_arrayi($persoon->get_email(), $foundEmailAdresses) &&
				!in_arrayi($persoon->get_email(), $this->settings->getBlacklist()) )
			{
				$wijk = $persoon->get_wijk();
				if(!isset($wijken[$wijk])){
					$wijken[$wijk] = [];
				}
				$wijken[$wijk][] = $persoon->get_email();
				$foundEmailAdresses[] = $persoon->get_email();
			}
		}
		return $wijken;
	}

	private function WriteWijkenToTextFile($wijken){
		$fileContent = "";
        $domain = $this->settings->GetEmailDomain();
		$alphas = range('a', 'z');
		uksort($wijken, "cmp_email");
		foreach($wijken as $wijk => $emails){
			$counter = 1;
			foreach($emails as $email){
				$suffix = self::SUFFIX_FLAG ? $alphas[floor($counter / 10)] : "";
				if($counter % 10 == 0){
					$fileContent .= "{$wijk}@{$domain},{$wijk}{$suffix}@{$domain}\r\n";
				}
				$fileContent .= "{$wijk}{$suffix}@{$domain},{$email}\r\n";
				$counter++;
			}
		}
		file_put_contents('gsuite-mailinglijst.txt', $fileContent);
	}
	
	private function WriteWijkenToJsonFile($wijken){
		$emailadresses = [];
        $domain = $this->settings->GetEmailDomain();
		$alphas = range('a', 'z');
		uksort($wijken, "cmp_email");
		foreach($wijken as $wijk => $emails){
			$counter = 1;
			asort($emails);
			foreach($emails as $email){
				$suffix = self::SUFFIX_FLAG ? $alphas[floor($counter / 10)] : "";
				if(self::SUFFIX_FLAG && $counter % 10 == 0){
					$emailadresses[]= ["{$wijk}@{$domain}", "{$wijk}{$suffix}@{$domain}"];
				}
				$emailadresses[]= ["{$wijk}{$suffix}@{$domain}", "{$email}"];
				
				$counter++;
			}
			if(self::SUFFIX_FLAG && count($emails) > 0){
				for($i = 0; $i < ceil(count($emails) / 10) ; $i++){
					$suffix = $alphas[$i];
					$emailadresses[]= ["{$wijk}@{$domain}", "{$wijk}{$suffix}@{$domain}"];
				}
			}
			//TODO remove 1 2 3 enz en only when noord oost zuid west
			$wijkrichting = preg_replace('/[0-9]+/', '', $wijk); //remove numbers
			if($wijkrichting != "nietingedeeld"){
				$emailadresses[]= ["{$wijkrichting}@{$domain}", "{$wijk}@{$domain}"];
			}
			
		}
		file_put_contents('scipio-gsuite-mailinglijst.json', json_encode($emailadresses));
		return $emailadresses;
	}
}