<?php

namespace App\Models;

use App\Libraries\NbnRecords;

/**
 * A facade for the NBN API
 *
 * Referenced at <https://api.nbnatlas.org/> the available search fields
 * listed at <https://records-ws.nbnatlas.org/index/fields>.
 */
class NbnQuery implements NbnQueryInterface
{
	/**
	 * Get an alphabetical list of species.
	 *
	 * e.g. https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=data_resource_uid:dr782&fq=taxon_name:B* AND species_group:Plants Bryophytes&pageSize=9&sort=
	 *
	 * Changing to use:
	 * https://records-ws.nbnatlas.org/occurrences/search?facets=common_name_and_lsid&q=data_resource_uid:dr782&flimit=-1&fq=common_name:Ivy*%20AND%20species_group:Plants+Bryophytes&fsort=index&pageSize=0
	 *
	 * TODO: Search in common names
	 * TODO: Search on axiophytes
	 * TODO: Only plants, only bryophytes or both
	 */
	public function getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup, $page)
	{
		//because the API respects the case
		$nameSearchString = ucfirst($nameSearchString);

		if ($speciesGroup === "both")
		{
			$speciesGroup = 'Plants+Bryophytes';
		}
		else
		{
			$speciesGroup = ucfirst($speciesGroup);
		}
		$nbnRecords           = new NbnRecords('/occurrences/search');
		$nbnRecords->facets   = 'common_name_and_lsid';
		$nbnRecords->flimit   = '10';
		//$nbnRecords->pageSize = 10;

		if ($nameType === "scientific")
		{
			$nbnRecords
				->add('taxon_name:' . str_replace(" ", "+%2B", $nameSearchString) . '*')
				->sort = "taxon_name";
		}

		if ($nameType === "common")
		{
			$nbnRecords
				->add('common_name:' . str_replace(" ", "+%2B", $nameSearchString) . '*')
				->sort = "common_name";
		}
		$nbnRecords->add('species_group:' . $speciesGroup);
		$queryUrl            = $nbnRecords->getPagingQueryStringWithFacetStart($page);
		$speciesListJson     = file_get_contents($queryUrl);
		$speciesList         = json_decode($speciesListJson);
		$speciesQueryResult  = new NbnQueryResult();

		if (count($speciesList->facetResults) > 0)
		{
			$speciesQueryResult->records = $speciesList->facetResults[0]->fieldResult;
		}
		else
		{
			$speciesQueryResult->records = [];
		}
			$speciesQueryResult->downloadLink = $nbnRecords->getDownloadQueryString();
			$speciesQueryResult->queryUrl     = $queryUrl;

		return $speciesQueryResult;
	}

	/**
	 * Get the records for a single species
	 *
	 * e.g. https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=9
	 *
	 * The taxon needs to be in double quotes so the complete string is searched for rather than a partial.
	 */
	public function getSingleSpeciesRecordsForCounty($speciesName, $page)
	{
		// mainly to replace the spaces with %20
		$speciesName      = rawurlencode($speciesName);
		$nbnRecords       = new NbnRecords('occurrences/search');
		$nbnRecords->sort = "year";
		$nbnRecords->dir  = "desc";
		// $nbnRecords->fsort = "index";
		$nbnRecords
			->add('taxon_name:' . '"' . $speciesName . '"');
		$queryUrl           = $nbnRecords->getPagingQueryStringWithStart($page);
		$recordsJson        = file_get_contents($queryUrl);
		$recordsJsonDecoded = json_decode($recordsJson);
		$recordList         = $recordsJsonDecoded->occurrences;
		$totalRecords       = $recordsJsonDecoded->totalRecords;
		usort($recordList, function ($a, $b) {
			return $b->year <=> $a->year;
		});

		$sites = [];
		foreach ($recordList as $record)
		{
			$record->locationId = $record->locationId ?? '';
			$record->collector  = $record->collector ?? 'Unknown';

			// To plot site markers on the map, we must capture the locationId
			// (site name) and latLong of only the _first_ record for each site.
			// The latLong returned from the API is a single string, so we
			// convert into an array of two floats.
			if (! array_key_exists($record->locationId, $sites))
			{
				$sites[$record->locationId] = array_map('floatval', explode(",", $record->latLong));
			}
		}

		$speciesQueryResult               = new NbnQueryResult();
		$speciesQueryResult->records      = $recordList;
		$speciesQueryResult->sites        = $sites;
		$speciesQueryResult->downloadLink = $nbnRecords->getDownloadQueryString();
		$speciesQueryResult->totalRecords = $totalRecords;
		$speciesQueryResult->queryUrl     = $queryUrl;
		return $speciesQueryResult;
	}

	/**
	 * Get a single record
	 *
	 * e.g. https://records-ws.nbnatlas.org/occurrence/4276e1be-b7d2-46b0-a33d-6fa82e97636a
	 */
	public function getSingleOccurenceRecord($uuid)
	{
		$nbnRecords = new NbnRecords('occurrence/');
		$recordJson = file_get_contents($nbnRecords->url() . $uuid);
		$record     = json_decode($recordJson);

		$singleOccurenceResult               = new NbnQueryResult();
		$singleOccurenceResult->records      = $record;
		$singleOccurenceResult->downloadLink = $nbnRecords->getDownloadQueryString();

		return $singleOccurenceResult;
	}

	/**
	 * Search for sites matching the string
	 *
	 * e.g. 'https://records-ws.nbnatlas.org/occurrences/search?fq=location_id:[Shrews%20TO%20*]&fq=data_resource_uid:dr782&facets=location_id&facet=on&pageSize=0';
	 */
	public function getSiteListForCounty($site_search_string)
	{
		$nbn_records           = new NbnRecords('occurrences/search');
		$nbn_records->facets   = "location_id";
		$nbn_records->pageSize = 0;
		$nbn_records
			->add('location_id:[' . $site_search_string . '%20TO%20*]');
		$query_url  = $nbn_records->getPagingQueryString();
		$sites_json = file_get_contents($query_url);
		$sites_list = json_decode($sites_json)->facetResults[0]->fieldResult;
		$sites_list = truncateArray(9, $sites_list);
		return $sites_list;
	}

	/**
	 * Get species list for a site.
	 *
	 * e.g. 'https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=&fq=data_resource_uid:dr782+AND+location_id:Shrewsbury+AND+species_group:Plants+Bryophytes&pageSize=9'
	 */
	public function getSpeciesListForSite($site_name, $species_group)
	{
		$nbn_records = new NbnRecords('explore/group/ALL_SPECIES');
		$nbn_records
			->add('location_id:' . urlencode($site_name))
			->add('species_group:Plants+Bryophytes');
		$query_url         = $nbn_records->getPagingQueryString();
		$species_json      = file_get_contents($query_url);
		$site_species_list = json_decode($species_json);
		return $site_species_list;
	}

	public function getSingleSpeciesRecordsForSite($site_name, $species_name)
	{
		return null;
	}

	public function getSpeciesListForSquare($grid_square, $species_group)
	{
		return null;
	}

	public function getSingleSpeciesRecordsForSquare($grid_square, $species_name)
	{
		return null;
	}
}
