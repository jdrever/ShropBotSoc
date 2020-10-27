<?php namespace App\Libraries;

interface NbnQueryInterface
{
  public static function getSpeciesInDataset($taxon_search_string, $name_type);
  public static function getRecordsForASpecies($taxon_name);
  public static function getSites($site_search_string);
  public static function getSiteSpeciesList($site_name);
  public static function getRecord($uuid);
}