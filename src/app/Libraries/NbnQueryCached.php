<?php namespace App\Libraries;

helper('nbn');
use App\Libraries\NbnQuery;

/**
 * Provides caching layer on top of the NbnQuery methods
 */
class NbnQueryCached implements NbnQueryInterface
{
  /**
   * Cache the query
   */
  public static function getSpeciesInDataset($taxon_search_string, $name_type)
  {
    // If the search field is empty, go to the begining of the alphabet
    if (IsNullOrEmptyString($taxon_search_string)) $taxon_search_string = "A"; 
    $taxon_search_string = ucfirst($taxon_search_string); //because the API respects the case
    $cache_name = "get-species-in_dataset-$name_type-$taxon_search_string";
    if ( ! $get_taxa = cache($cache_name))
    {
        $get_taxa = NbnQuery::getSpeciesInDataset($taxon_search_string, $name_type);
        cache()->save($cache_name, $get_taxa, CACHE_LIFE);
    }
    return $get_taxa;
  }

  /**
   * TODO: caching
   */
  public static function getRecordsForASpecies($taxon_name)
  {
    return NbnQuery::getRecordsForASpecies($taxon_name);
  }

  /**
   * TODO: caching
   */
  public static function getSites($site_search_string)
  {
    return NbnQuery::getSites($site_search_string);
  }

  /**
   * TODO: caching
   */
  public static function getSiteSpeciesList($site_name)
  {
    return NbnQuery::getSiteSpeciesList($site_name);
  }

  /**
   * TODO: caching
   */
  public static function getRecord($uuid)
  {
    return NbnQuery::getRecord($uuid);
  }

}
