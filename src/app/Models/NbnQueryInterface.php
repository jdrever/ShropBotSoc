<?php

namespace App\Models;

interface NbnQueryInterface
{
	public function getSpeciesListForCounty($name_search_string, $name_type, $species_group, $axiophyte_filter, $page);
	public function getSingleSpeciesRecordsForCounty($species_name, $page);
	public function getSingleOccurenceRecord($uuid);

	public function getSiteListForCounty($siteSearchString, $page);
	public function getSpeciesListForSite($siteName, $nameType, $speciesGroup, $axiophyteFilter, $page);
	public function getSingleSpeciesRecordsForSite($site_name, $species_name,$page);

	public function getSpeciesListForSquare($gridSquare, $speciesGroup, $nameType, $axiophyteFilter, $page);
	public function getSingleSpeciesRecordsForSquare($gridSquare, $speciesName, $page);
}
