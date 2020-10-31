<?php namespace App\Models;

interface NbnQueryInterface
{
  public function getSpeciesListForCounty($name_search_string, $name_type, $plant_group);
  public function getSingleSpeciesRecordsForCounty($species_name);
  public function getSingleOccurenceRecord($uuid);

  public function getSiteListForCounty($site_search_string);
  public function getSpeciesListForSite($site_name);
  public function getSingleSpeciesRecordsForSite($site_name, $species_name);

  public function getSpeciesListForSquare($grid_square);
  public function getSingleSpeciesRecordsForSquare($grid_square, $species_name);
}