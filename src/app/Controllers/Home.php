<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$this->data['title'] = 'Testing';

		$environment_array = getenv();
		$this->data['environment_array'] = preg_grep('!^GAE_!', $environment_array);

		echo view('welcome', $this->data);
	}
}
