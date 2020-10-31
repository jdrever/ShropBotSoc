<?php namespace App\Models;

interface NbnQueryInterface
{
  public function getSpeciesInDataset($taxon_search_string, $name_type);
  public function getRecordsForASpecies($taxon_name);
  public function getSites($site_search_string);
  public function getSiteSpeciesList($site_name);
  public function getRecord($uuid);
}