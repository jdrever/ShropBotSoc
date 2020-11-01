<?php namespace App\Libraries;

/**
 * Facade for the NBN records end point
 */
class NbnRecords
{
  const BASE_URL = 'https://records-ws.nbnatlas.org/';
  public $data_resource_uid = 'dr782'; // The SEDN data set
  public $facets;
  public $fsort;
  public $path = '';
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
    $query_string = $url.'?';
    $query_string .= 'q=data_resource_uid:'.$this->data_resource_uid.'&';
    $query_string .= 'fq='.implode("%20AND%20", $this->filter_query_parameters).'&';
    $query_string .= 'facets='.$this->facets.'&';
    $query_string .= 'sort='.$this->sort.'&';
    $query_string .= 'fsort='.$this->fsort.'&';
    return $query_string;
  }

  /**
   * Return the base url and path (really only used for getting a single occurence record)
   */
  function url()
  {
    return $this::BASE_URL.$this->path;
  }

  /**
   * Return the query string for paging
   * 
   * @return string
   */
  function getPagingQueryString()
  {
    $query_string = $this->getQueryString($this::BASE_URL.$this->path);
    $query_string .= 'pageSize='.$this->pageSize;
    return $query_string;
  }

  /**
   * Return the query string for downloading the data
   * 
   * @return string
   */
  function getDownloadQueryString()
  {
    $query_string = $this->getQueryString($this::BASE_URL.'occurrences/index/download');
    $query_string .= '&reasonTypeId=11&fileType=csv';
    return $query_string;
  }

  /**
   * @var string[]
   */
  protected $filter_query_parameters = array();

  /**
   * Adds to the internal list of filter query parameters
   *
   * @return $this
   */
  public function add($filter_query_parameter)
  {
      $this->filter_query_parameters[] = $filter_query_parameter;
      return $this;
  }

}

