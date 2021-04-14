<?php namespace App\Libraries;

/**
 * Facade for the NBN records end point
 *
 * See the NBN Atlas Query Primer for details about using the API
 * https://docs.google.com/document/d/1FiVasGGZ3kRPnu5347GPAef7Tr5LvvghCS6x82xnfu4/edit
 *
 * @package Libraries
 * @author  Careful Digital <hello@careful.digital>
 * @license https://www.shropshirebotany.org.uk/ Shropshire Botanical Society
 */

class NbnRecords
{
	const BASE_URL = 'https://records-ws.nbnatlas.org/';

	/**
	 * The unique data resource id code
	 *
	 * The id can by found by searching the NBN Atlas data sets at
	 * https://registry.nbnatlas.org/datasets. The id is located in the URL of a
	 * data resource page and consists of the letters "dr" followed by a number;
	 * e.g., https://registry.nbnatlas.org/public/showDataResource/dr782
	 *
	 * dr782 is the SEDN data set.
	 *
	 * Use dr1323 for Worcestershire data if SEDN data not available:
	 * https://registry.nbnatlas.org/public/showDataResource/dr1323
	 *
	 * @var string $dataResourceUid
	 */
	private $dataResourceUid = 'dr782';

	/**
	 * TODO: Describe what the $path member variable is for
	 *
	 * @var string $path
	 */
	private $path = '';

	/**
	 * TODO: Describe what the $facets member variable is for
	 *
	 * @var string $facets
	 */
	public $facets;

	/**
	 * TODO: Describe what the $fsort member variable is for
	 *
	 * @var string $fsort
	 */
	public $fsort;

	/**
	 * Sets the number of paged records returned by each NBN query
	 *
	 * @var integer $pageSize
	 */
	public $pageSize = 10;

	/**
	 * TODO: Describe what the $sort member variable is for
	 *
	 * @var string $sort
	 */
	public $sort;

	/**
	 * Constructor
	 *
	 * Accepts a path fragment which indicates the NBN Atlas API search type to
	 * perform. Defaults to Occurrence search: https://api.nbnatlas.org/#ws3
	 *
	 * See https://api.nbnatlas.org/ for others.
	 *
	 * @param string $path NBN Atlas API search type
	 */
	public function __construct(string $path = 'occurrences/search')
	{
		$this->path = $path;
	}

	/**
	 * Return the base search query string
	 *
	 * @param string $url The full url to query
	 *
	 * @return string
	 */
	private function getQueryString(string $url)
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
	 * Return the base url and path (really only used for getting a single
	 * occurence record)
	 *
	 * @return string
	 */

	public function url()
	{
		return $this::BASE_URL . $this->path;
	}

	/**
	 * Return the query string for paging
	 *
	 * @return string
	 */
	public function getPagingQueryString()
	{
		$queryString  = $this->getQueryString($this::BASE_URL . $this->path);
		$queryString .= 'pageSize=' . $this->pageSize;
		return $queryString;
	}

	public function getPagingQueryStringWithStart($start)
	{
		$pagingQuery = $this->getPagingQueryString();
		return $pagingQuery .= "&start=" . $start;
	}

	/**
	 * Return the query string for downloading the data
	 *
	 * @return string
	 */
	public function getDownloadQueryString()
	{
		$queryString  = $this->getQueryString($this::BASE_URL . 'occurrences/index/download');
		$queryString .= '&reasonTypeId=11&fileType=csv';
		return $queryString;
	}

	/**
	 * Keeps an internal array of query filter parameters.
	 *
	 * A list of available index fields can be found at
	 * https://species-ws.nbnatlas.org/admin/indexFields
	 *
	 * @var string[] Array of strings
	 */
	protected $filterQueryParameters = [];

	/**
	 * Adds to the internal list of filter query parameters
	 *
	 * A list of available index fields can be found at
	 * https://species-ws.nbnatlas.org/admin/indexFields
	 *
	 * @param string $filterQueryParameter A single filter query parameter
	 *
	 * @return $this
	 */
	public function add(string $filterQueryParameter)
	{
		$this->filterQueryParameters[] = $filterQueryParameter;
		return $this;
	}
}
