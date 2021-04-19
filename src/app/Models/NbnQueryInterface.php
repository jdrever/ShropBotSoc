<?php

namespace App\Models;

interface NbnQueryInterface
{
	public function getSpeciesListForCounty($name_search_string, $name_type, $species_group, $page);
	public function getSingleSpeciesRecordsForCounty($species_name, $page);
	public function getSingleOccurenceRecord($uuid);

	public function getSiteListForCounty($site_search_string);
	public function getSpeciesListForSite($site_name, $species_group);
	public function getSingleSpeciesRecordsForSite($site_name, $species_name);

	public function getSpeciesListForSquare($grid_square, $species_group);
	public function getSingleSpeciesRecordsForSquare($grid_square, $species_name);
}
