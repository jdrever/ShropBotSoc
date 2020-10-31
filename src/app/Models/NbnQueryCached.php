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
  public function getSpeciesInDataset($taxon_search_string, $name_type)
  {
    $taxon_search_string = ucfirst($taxon_search_string); //because the API respects the case
    $cache_name = "get-species-in_dataset-$name_type-$taxon_search_string";
    if ( ! $get_taxa = cache($cache_name))
    {
        $get_taxa = $this->nbnQuery->getSpeciesInDataset($taxon_search_string, $name_type);
        cache()->save($cache_name, $get_taxa, CACHE_LIFE);
    }
    return $get_taxa;
  }

  /**
   * TODO: caching
   */
  public function getRecordsForASpecies($taxon_name)
  {
    return $this->nbnQuery->getRecordsForASpecies($taxon_name);
  }

  /**
   * TODO: caching
   */
  public function getSites($site_search_string)
  {
    return $this->nbnQuery->getSites($site_search_string);
  }

  /**
   * TODO: caching
   */
  public function getSiteSpeciesList($site_name)
  {
    return $this->nbnQuery->getSiteSpeciesList($site_name);
  }

  /**
   * TODO: caching
   */
  public function getRecord($uuid)
  {
    return $this->nbnQuery->getRecord($uuid);
  }

}
