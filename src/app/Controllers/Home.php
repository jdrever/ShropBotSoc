<?php namespace App\Controllers;

helper('gae');

class Home extends BaseController
{
	public function index()
	{
		$data['title'] = 'Testing';
		$data['gae_environment'] = get_gae_environment();
		echo view('welcome', $data);
	}

}
