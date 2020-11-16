<?php namespace App\Controllers;

class About extends BaseController
{
	public function index()
	{
		$data['title'] = 'Testing';
		$data['content'] = 'Not running on GAE (caching in place).';
		if (getenv('IS_GAE') == "TRUE")
		{
			$data['content'] = 'Running on GAE Standard Environment (no caching).';
		}
		echo view('about', $data);
	}

}
