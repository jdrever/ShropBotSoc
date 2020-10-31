<?php namespace App\Libraries;

/**
 * Facade for the NBN records end point
 */
class NbnRecords
{
  const BASE_URL = 'https://records-ws.nbnatlas.org/';
  public $path = '';
  public $pageSize = 9;
  public $data_resource_uid = 'dr782'; // The SEDN data set

  function __construct($path = 'occurrences/search') 
  {
    $this->path = $path;
  }

  /**
   * Return the base url and path
   */
  function url()
  {
    return $this::BASE_URL.$this->path.'?';
  }

  /**
   * Return the formated query parameters
   * 
   * @return string
   */
  function query_string()
  {
    $query_string .= '{$this->BASE_URL}';
    $query_string .= 'f=data_resource_uid:{$data_resource_uid}&';
    $query_string .= 'fq=&';
    $query_string .= 'facets=&';
    $query_string .= 'facets={$pageSize}';
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

