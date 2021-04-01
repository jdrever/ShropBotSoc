<?php namespace App\Models;

/**
 * Provides caching layer on top of the NbnQuery methods
 */
class NbnQueryCached implements NbnQueryInterface
{

  function __construct() {
    $this->nbnQuery = model('App\Models\NbnQuery', false);
  }

  /**
   * Cache the search for species within the county
   *
   * @return
   */
  public function getSpeciesListForCounty($name_search_string, $name_type, $species_group)
  {
    $name_search_string = ucfirst($name_search_string); //because the API respects the case
    $cache_name = "get-species-list-for-county-$name_type-$species_group-$name_search_string";
    if ( ! $species_list = cache($cache_name)&&false) //turn off caching for testing
    {
        $species_list = $this->nbnQuery->getSpeciesListForCounty($name_search_string, $name_type, $species_group);
    //    cache()->save($cache_name, $species_list, CACHE_LIFE);
    //}
    return $species_list;
  }

  /**
   * TODO: caching
   */
  public function getSingleSpeciesRecordsForCounty($species_name)
  {

    return $this->nbnQuery->getSingleSpeciesRecordsForCounty($species_name);
  }

  /**
   * TODO: caching
   */
  public function getSingleOccurenceRecord($uuid)
  {
    return $this->nbnQuery->getSingleOccurenceRecord($uuid);
  }

  /**
   * Cache the site list for the county
   */
  public function getSiteListForCounty($site_search_string)
  {
    $name_search_string = ucfirst($site_search_string);
    $cache_name = "get-site-list-for-county-$name_search_string";
    if ( ! $site_list = cache($cache_name))
    {
        $site_list = $this->nbnQuery->getSiteListForCounty($site_search_string);
        cache()->save($cache_name, $site_list, CACHE_LIFE);
    }
    return $site_list;
  }

  /**
   * TODO: caching
   */
  public function getSpeciesListForSite($site_name, $species_group)
  {
    return $this->nbnQuery->getSpeciesListForSite($site_name, $species_group);
  }

  /**
   * TODO: caching
   */
  public function getSingleSpeciesRecordsForSite($site_name, $species_name)
  {
    return $this->nbnQuery->getSingleSpeciesRecordsForSite($site_name, $species_name);
  }

  /**
   * TODO: caching
   */
  public function getSpeciesListForSquare($grid_square, $species_group)
  {
    return $this->nbnQuery->getSpeciesListForSquare($grid_square);
  }

  /**
   * TODO: caching
   */
  public function getSingleSpeciesRecordsForSquare($grid_square, $species_name)
  {
    return $this->nbnQuery->getSingleSpeciesRecordsForSquare($grid_square, $species_name);
  }

}
