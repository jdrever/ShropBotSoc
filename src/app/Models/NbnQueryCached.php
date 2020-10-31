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
   * Cache the query
   * 
   * @return 
   */
  public function getSpeciesListForCounty($name_search_string, $name_type, $species_group)
  {
    $name_search_string = ucfirst($name_search_string); //because the API respects the case
    $cache_name = "get-species-in_dataset-$name_type-$species_group-$name_search_string";
    if ( ! $get_taxa = cache($cache_name))
    {
        $get_taxa = $this->nbnQuery->getSpeciesListForCounty($name_search_string, $name_type, $species_group);
        cache()->save($cache_name, $get_taxa, CACHE_LIFE);
    }
    return $get_taxa;
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
   * TODO: caching
   */
  public function getSiteListForCounty($site_search_string)
  {
    return $this->nbnQuery->getSiteListForCounty($site_search_string);
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