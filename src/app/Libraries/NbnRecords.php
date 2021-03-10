<?php namespace App\Libraries;

/**
 * Facade for the NBN records end point
 */
class NbnRecords
{
	const BASE_URL          = 'https://records-ws.nbnatlas.org/';
	public $dataResourceUid = 'dr1323'; //  782=The SEDN data set.  Use 1323 for Worcestershire data if Shrop data not available
	public $facets;
	public $fsort;
	public $path     = '';
	public $pageSize = 9;
	public $sort;

	function __construct($path = 'occurrences/search')
	{
		$this->path = $path;
	}

	/**
	 * Return the base search query string
	 *
	 * @return string
	 */
	function getQueryString($url)
	{
		$queryString  = $url . '?';
		$queryString .= 'q=data_resource_uid:' . $this->dataResourceUid . '&';
		$queryString .= 'fq=' . implode('%20AND%20', $this->filterQueryParameters) . '&';
		$queryString .= 'facets=' . $this->facets . '&';
		$queryString .= 'sort=' . $this->sort . '&';
		$queryString .= 'fsort=' . $this->fsort . '&';
		return $queryString;
	}

	/**
	 * Return the base url and path (really only used for getting a single occurence record)
	 */
	function url()
	{
		return $this::BASE_URL . $this->path;
	}

	/**
	 * Return the query string for paging
	 *
	 * @return string
	 */
	function getPagingQueryString()
	{
		$queryString  = $this->getQueryString($this::BASE_URL . $this->path);
		$queryString .= 'pageSize=' . $this->pageSize;
		return $queryString;
	}

	/**
	 * Return the query string for downloading the data
	 *
	 * @return string
	 */
	function getDownloadQueryString()
	{
		$queryString  = $this->getQueryString($this::BASE_URL . 'occurrences/index/download');
		$queryString .= '&reasonTypeId=11&fileType=csv';
		return $queryString;
	}

	/**
	 * @var string[]
	 */
	protected $filterQueryParameters = [];

	/**
	 * Adds to the internal list of filter query parameters
	 *
	 * @return $this
	 */
	public function add($filterQueryParameter)
	{
		$this->filterQueryParameters[] = $filterQueryParameter;
		return $this;
	}

}
