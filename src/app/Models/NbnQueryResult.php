<?php

namespace App\Models;

class NbnQueryResult
{
	public $records;
	public $sites;
	public $downloadLink;
	public $totalRecords;
	public $queryUrl;
	public $status;
	public $message;

	public function getTotalPages()
	{
		$limit = 10; //per page
		return ceil($this->totalRecords / $limit); //calculate total pages
	}
}
