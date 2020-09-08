<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$this->data['title'] = 'Testing';
		echo view('welcome', $this->data);
	}

}
