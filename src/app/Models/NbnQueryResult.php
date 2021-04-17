<?php

namespace App\Models;

class NbnQueryResult
{
	public $records;
	public $downloadLink;
	public $totalRecords;
	public $queryUrl;

	public function getTotalPages()
	{
		$limit = 10; //per page
		return ceil($this->totalRecords / $limit); //calculate total pages
	}
}
