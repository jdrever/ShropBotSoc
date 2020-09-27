<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data['title'] = 'Testing';
		$data['gae_environment'] = $this->get_gae_environment();
		echo view('welcome', $data);
	}

	/**
	 * GAE provides environment variables, create an array so they can be
	 * displayed in a table.
	 */
	private function get_gae_environment()
	{
		$environment_array = getenv();
		$gae_environment_array = [];
		foreach($environment_array AS $key => $val)
			//if(strpos(" ".$key, "GAE_") == 1)    
				array_push($gae_environment_array, array($key, $val)); 
		return $gae_environment_array;
	}
}
