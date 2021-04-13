<?php namespace App\Models;


class NbnQueryResult
{
	public $records;
	public $downloadLink;


	public function paginate()
	{
		$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
		$total = count($this->records ); //total items in array
		$limit = 10; //per page
		$totalPages = ceil( $total/ $limit ); //calculate total pages
		$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
		$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
		$offset = ($page - 1) * $limit;
		if( $offset < 0 ) $offset = 0;

		return array_slice( $this->records, $offset, $limit );
	}
}

