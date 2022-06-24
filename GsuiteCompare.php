<?php
/**
 * Gsuite data must be available in config/google-routing-current.json
 * Then this class can compare actual gsuite with expected from scipio.
 */
class GSuiteCompare{
	public function extract(){
		$emailadresses = json_decode(file_get_contents("config/google-routing-current.json"));
		return $emailadresses;
	}
	
	public function write_gsuite_2_text(){
		$emailadresses = json_decode(file_get_contents("config/google-routing-current.json"));
		usort($emailadresses, "cmp_email_array");
		$fileContent = "";
		foreach($emailadresses as $emailadresPair){
			$fileContent .= "{$emailadresPair[0]},{$emailadresPair[1]}\r\n";
		}
		file_put_contents('gsuite-mailinglijst-actual.txt', $fileContent);
	}
	
	public function write_compare_emailadresses_scipio($actual_gsuite, $expected_scipio){
		$diff = array_udiff($expected_scipio, $actual_gsuite, "cmp_email_array");
		file_put_contents('diff-to-add.json', json_encode($diff));
		
		$fileContent = "";
		foreach($diff as $emailadresPair){
			$fileContent .= "{$emailadresPair[0]},{$emailadresPair[1]}\r\n";
		}
		file_put_contents('diff-to-add-comma-seperated.txt', strtolower($fileContent));
	}

	public function write_compare_emailadresses_gsuite($actual_gsuite, $expected_scipio){
		$diff = array_udiff($actual_gsuite, $expected_scipio, "cmp_email_array");
		file_put_contents('diff-to-remove.json', json_encode($diff));
		
	}
}