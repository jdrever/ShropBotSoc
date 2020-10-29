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
   * @var string[]
   */
  protected $query_parameters = array();

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
  function formatted_query_string()
  {
    return "";
  }

  /**
   * Adds an element to the internal list
   * 
   * Modelled on <https://github.com/makinacorpus/php-lucene-query>
   *
   * @return $this
   */
  public function add($query_parameter)
  {
      $this->query_parameters[] = $query_parameter;
      return $this;
  }

}

