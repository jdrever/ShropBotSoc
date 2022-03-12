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
	 * Determines whether fall back to local JSON files is active
	 * Set to false on production environments
	 *
	 * @var    bool
	 * @access private
	 */
	private const LOCAL_FALLBACK_ACTIVE = false;

	/**
	 * Determines the timeout for the NBN API call
	 * Set low if testing and wanting quick turnaround
	 *
	 * @var    int
	 * @access private
	 */
	private const API_TIMEOUT = 30;

	/**
	 * Get an alphabetical list of species.
	 *
	 * e.g. https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=data_resource_uid:dr782&fq=taxon_name:B* AND species_group:Plants Bryophytes&pageSize=9&sort=
	 *
	 * Changing to use:
	 * https://records-ws.nbnatlas.org/occurrences/search?facets=common_name_and_lsid&q=data_resource_uid:dr782&flimit=-1&fq=common_name:Ivy*%20AND%20species_group:Plants+Bryophytes&fsort=index&pageSize=0
	 *
	 */
	public function getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup, $axiophyteFilter, $page)
	{
		//because the API respects the case
		$nameSearchString = ucfirst($nameSearchString);

		$nbnRecords           = new NbnRecords('/occurrences/search');

		//$nbnRecords->pageSize = 10;
		$preparedName=$this->prepareSearchString($nameSearchString);
		if ($nameType === "scientific")
		{

			$nbnRecords->add('taxon_name:' . $preparedName);
			$nbnRecords->facets   = 'names_and_lsid';
			$nbnRecords->fsort = "index";
		}

		if ($nameType === "common")
		{
			$nbnRecords->add('common_name:' . $preparedName);
			$nbnRecords->facets   = 'common_name_and_lsid';
			$nbnRecords->fsort = "index";
		}

		if ($axiophyteFilter === "true")
		{
			$nbnRecords->addAxiophyteFilter();
		}

		$speciesGroup = ucfirst($speciesGroup);
		if ($speciesGroup=== "Plants")
		{
			$nbnRecords->add('species_group:' . "Plants");
			$nbnRecords->addNot('species_group:' . "Bryophytes");
		}
		else if ($speciesGroup=== "Bryophytes")
		{
			$nbnRecords->add('species_group:' . "Bryophytes");
		}
		else
		{
			$nbnRecords->add('species_group:' . 'Plants+OR+Bryophytes');
		}
		$queryUrl            = $nbnRecords->getUnpagedQueryString();
		$nbnQueryResponse    = $this->callNbnApi($queryUrl);
		$totalRecords 		 = 0;
		if (isset($nbnQueryResponse->jsonResponse->facetResults[0]))
		{
			$totalRecords = count($nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult);
		}
		$nbnRecords->flimit   = '10';
		$queryUrl            = $nbnRecords->getPagingQueryStringWithFacetStart($page);
		$nbnQueryResponse = $this->callNbnApi($queryUrl);

		// get local file if NBN not responding and fallback active
		if ($nbnQueryResponse->status === 'ERROR' && self::LOCAL_FALLBACK_ACTIVE)
		{
			$queryUrl ='http://localhost:8089/json/county-species-hedera.json';
			$nbnQueryResponse = $this->callNbnApi($queryUrl);
			$nbnQueryResponse->message = 'NBN down.  Falling back to local file.';
		}
		$speciesQueryResult  = new NbnQueryResult();

		if ($nbnQueryResponse->status === 'OK')
		{
			if (isset($nbnQueryResponse->jsonResponse->facetResults[0]))
			{
				$speciesQueryResult->records = 	$nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult;

			}
			else
			{
				$speciesQueryResult->records = [];
			}
			$speciesQueryResult->downloadLink = $nbnRecords->getDownloadQueryString();
		}
		$speciesQueryResult->status   	  = $nbnQueryResponse->status;
		$speciesQueryResult->message  	  = $nbnQueryResponse->message;
		$speciesQueryResult->queryUrl     = $queryUrl;
		$speciesQueryResult->totalRecords = $totalRecords;
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

		$nbnQueryResponse = $this->callNbnApi($queryUrl);
		$speciesQueryResult               = new NbnQueryResult();
		if ($nbnQueryResponse->status === 'OK' )
		{
			$recordList         = $nbnQueryResponse->jsonResponse->occurrences;
			$totalRecords       = $nbnQueryResponse->jsonResponse->totalRecords;
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
				if (! array_key_exists($record->locationId, $sites)&& isset($record->latLong))
				{
					$sites[$record->locationId] = array_map('floatval', explode(",", $record->latLong));
				}
			}
			$speciesQueryResult->records      = $recordList;
			$speciesQueryResult->sites        = $sites;
			$speciesQueryResult->downloadLink = $nbnRecords->getDownloadQueryString();
			$speciesQueryResult->totalRecords = $totalRecords;
		}

		$speciesQueryResult->status       = $nbnQueryResponse->status;
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
		$nbnRecords            = new NbnRecords('occurrence/');
		$queryUrl              = $nbnRecords->url() . $uuid;
		$nbnQueryResponse      = $this->callNbnApi($queryUrl);
		$singleOccurenceResult = new NbnQueryResult();

		if ($nbnQueryResponse->status === 'OK')
		{
			$singleOccurenceResult->records      = $nbnQueryResponse->jsonResponse;
			//$singleOccurenceResult->downloadLink = $nbnRecords->getDownloadQueryString();
		}
		$singleOccurenceResult->status   = $nbnQueryResponse->status;
		$singleOccurenceResult->message  = $nbnQueryResponse->message;
		$singleOccurenceResult->queryUrl = $queryUrl;

		$nbnRecord = new NbnRecords("occurrences/index/download");
		$singleOccurenceResult->downloadLink = $nbnRecords->getSingleRecordDownloadQueryString($singleOccurenceResult->records->raw->occurrence->occurrenceID);
		return $singleOccurenceResult;
	}

	/**
	 * Search for sites matching the string
	 *
	 * e.g. 'https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782%20AND%20&location_id:Shrews*&fq=species_group:Plants+OR+Bryophytes&facets=location_id&facet=on&pageSize=0';
	 */
	public function getSiteListForCounty($siteSearchString, $page)
	{
		// API respects case - upper case all words in search string
		$siteSearchString = ucwords($siteSearchString);
		// Replace spaces with "\%20" so the query searches for the whole string
		$siteSearchString = str_replace(" ", "\%20", $siteSearchString);

		$nbnRecords           = new NbnRecords('occurrences/search');
		$nbnRecords->facets   = "location_id";

		$nbnRecords->addExtraQueryParameter('location_id:'.$siteSearchString.'*');
		$nbnRecords->add('species_group:Plants+OR+Bryophytes');

		$queryUrl            = $nbnRecords->getUnpagedQueryString();
		$nbnQueryResponse    = $this->callNbnApi($queryUrl);
		$totalRecords 		 = 0;
		if (isset($nbnQueryResponse->jsonResponse->facetResults[0]))
		{
			$totalRecords = count($nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult);
		}

		$nbnRecords->flimit   = '10';
		$queryUrl  = $nbnRecords->getPagingQueryStringWithFacetStart($page);
		$nbnQueryResponse = $this->callNbnApi($queryUrl);

		$sitesQueryResult  = new NbnQueryResult();

		if ($nbnQueryResponse->status === 'OK' && isset($nbnQueryResponse->jsonResponse->facetResults[0]))
		{
			$sitesQueryResult->sites = $nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult;
		}
		else
		{
			$sitesQueryResult->sites = [];
		}

		$sitesQueryResult->status   = $nbnQueryResponse->status;
		$sitesQueryResult->message  = $nbnQueryResponse->message;
		$sitesQueryResult->queryUrl = $queryUrl;
		$sitesQueryResult->totalRecords = $totalRecords;

		return $sitesQueryResult;
	}

	/**
	 * Get species list for a site.
	 * e.g. 'https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=&fq=data_resource_uid:dr782+AND+location_id:Shrewsbury+AND+species_group:Plants+Bryophytes&pageSize=9
	 *
	 * Changed to use 'https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=location_id:Shrewsbury+AND+species_group:Plants+Bryophytes
	 */
	public function getSpeciesListForSite($siteName, $nameType, $speciesGroup, $axiophyteFilter, $page)
	{
		$nbnRecords = new NbnRecords('occurrences/search');

		$speciesGroup = ucfirst($speciesGroup);
		if ($speciesGroup=== "Plants")
		{
			$nbnRecords->add('species_group:' . "Plants");
			$nbnRecords->addNot('species_group:' . "Bryophytes");
		}
		else if ($speciesGroup=== "Bryophytes")
		{
			$nbnRecords->add('species_group:' . "Bryophytes");
		}
		else
		{
			$nbnRecords->add('species_group:Bryophytes+OR+Plants');
		}

		if ($nameType === "scientific")
		{
			$nbnRecords->facets   = 'names_and_lsid';
		}

		if ($nameType === "common")
		{
			$nbnRecords->facets   = 'common_name_and_lsid';
		}

		if ($axiophyteFilter === "true")
		{
			$nbnRecords->addAxiophyteFilter();
		}

		$nbnRecords->fsort   = 'index';

		$nbnRecords->add('location_id:' . str_replace(" ", "\%20", $siteName)); // Use "\ " instead of spaces to search only for species matching whole site name
		$queryUrl         = $nbnRecords->getUnpagedQueryString();
		$nbnQueryResponse = $this->callNbnApi($queryUrl);

		$totalRecords = 0;
		if (isset($nbnQueryResponse->jsonResponse->facetResults[0]))
		{
			$totalRecords = count($nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult);
		}

		$nbnRecords->flimit = '10';
		$queryUrl = $nbnRecords->getPagingQueryStringWithFacetStart($page);
		$nbnQueryResponse = $this->callNbnApi($queryUrl);

		$speciesQueryResult = new NbnQueryResult();

		if ($nbnQueryResponse->status === 'OK' && isset($nbnQueryResponse->jsonResponse->facetResults[0]))
		{
			$speciesQueryResult->records = $nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult;
		}
		else
		{
			$speciesQueryResult->records = [];
		}

		// Get site location from first occurrence
		if (isset($nbnQueryResponse->jsonResponse->occurrences[0]->decimalLatitude))
		{
			$speciesQueryResult->siteLocation = [$nbnQueryResponse->jsonResponse->occurrences[0]->decimalLatitude, $nbnQueryResponse->jsonResponse->occurrences[0]->decimalLongitude];
		}
		else
		{
			// No location data - currently just doesn't show a site marker
			$speciesQueryResult->siteLocation = [];
		}

		$speciesQueryResult->status   = $nbnQueryResponse->status;
		$speciesQueryResult->message  = $nbnQueryResponse->message;
		$speciesQueryResult->queryUrl = $queryUrl;
		$speciesQueryResult->totalRecords = $totalRecords;

		return $speciesQueryResult;
	}

	public function getSingleSpeciesRecordsForSite($siteName, $speciesName, $page)
	{
		// mainly to replace the spaces with %20
		$speciesName      = rawurlencode($speciesName);
		$nbnRecords       = new NbnRecords('occurrences/search');
		$nbnRecords->sort = "year";
		$nbnRecords->dir  = "desc";
		// $nbnRecords->fsort = "index";
		$nbnRecords
			->add('taxon_name:' . '"' . $speciesName . '"')
			->add('location_id:' . '"' . urlencode($siteName) . '"');
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
			if (! array_key_exists($record->locationId, $sites)&& isset($record->latLong))
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


	public function getSpeciesListForSquare($gridSquare, $speciesGroup, $nameType, $page)
	{
		$nbnRecords       = new NbnRecords('occurrences/search');

		$speciesGroup = ucfirst($speciesGroup);
		if ($speciesGroup === "Plants")
		{
			$nbnRecords->add('species_group:' . "Plants");
			$nbnRecords->addNot('species_group:' . "Bryophytes");
		}
		else if ($speciesGroup === "Bryophytes")
		{
			$nbnRecords->add('species_group:Bryophytes');
		}
		else
		{
			$nbnRecords->add('species_group:Plants');
		}

		if ($nameType === "scientific" || $nameType === "axiophyte")
		{
			$nbnRecords->facets   = 'names_and_lsid';
		}
		if ($nameType === "axiophyte")
		{
			$nbnRecords->addAxiophyteFilter();
		}
		if ($nameType === "common")
		{
			$nbnRecords->facets   = 'common_name_and_lsid';
		}

		$nbnRecords->add('grid_ref_1000:"' . rawurlencode($gridSquare) . '"');

		$nbnRecords->fsort   = 'index';
		$queryUrl         = $nbnRecords->getUnpagedQueryString();
		$nbnQueryResponse = $this->callNbnApi($queryUrl);

		$totalRecords = 0;
		if (isset($nbnQueryResponse->jsonResponse->facetResults[0]))
		{
			$totalRecords = count($nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult);
		}

		$nbnRecords->flimit = '10';
		$queryUrl = $nbnRecords->getPagingQueryStringWithFacetStart($page);
		$nbnQueryResponse = $this->callNbnApi($queryUrl);

		$speciesQueryResult = new NbnQueryResult();

		if ($nbnQueryResponse->status === 'OK')
		{
			if (isset($nbnQueryResponse->jsonResponse->facetResults[0]))
			{
				$speciesQueryResult->records = $nbnQueryResponse->jsonResponse->facetResults[0]->fieldResult;
			}
			else
			{
				$speciesQueryResult->records = [];
			}

			$speciesQueryResult->downloadLink = $nbnRecords->getDownloadQueryString();
			$speciesQueryResult->totalRecords = $totalRecords;
		}

		$speciesQueryResult->status   = $nbnQueryResponse->status;
		$speciesQueryResult->message  = $nbnQueryResponse->message;
		$speciesQueryResult->queryUrl = $queryUrl;

		return $speciesQueryResult;

	}

	public function getSingleSpeciesRecordsForSquare($gridSquare, $speciesName, $page)
	{
		// mainly to replace the spaces with %20
		$speciesName      = rawurlencode($speciesName);
		$nbnRecords       = new NbnRecords('occurrences/search');
		$nbnRecords->sort = "year";
		$nbnRecords->dir  = "desc";
		$nbnRecords
			->add('taxon_name:' . '"' . $speciesName . '"')
			->add('grid_ref_1000:' . '"' . urlencode($gridSquare) . '"');
		$queryUrl           = $nbnRecords->getPagingQueryStringWithStart($page);

		$nbnQueryResponse 	= $this->callNbnApi($queryUrl);
		$speciesQueryResult = new NbnQueryResult();
		if ($nbnQueryResponse->status === 'OK' )
		{
			$recordList         = $nbnQueryResponse->jsonResponse->occurrences;
			$totalRecords       = $nbnQueryResponse->jsonResponse->totalRecords;
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
			$speciesQueryResult->records      = $recordList;
			$speciesQueryResult->sites        = $sites;
			$speciesQueryResult->downloadLink = $nbnRecords->getDownloadQueryString();
			$speciesQueryResult->totalRecords = $totalRecords;
		}

		$speciesQueryResult->status       = $nbnQueryResponse->status;
		$speciesQueryResult->queryUrl     = $queryUrl;
		return $speciesQueryResult;
	}


	/**
	 * Deals with multi-word search terms and prepares
	 * theme for use by the NBN API by adding ANDs and
	 * setting to all lower case
	 *
	 * @param string $searchString the search term to prepare
	 *
	 * @return string the prepared search search name
	 */
	private function prepareSearchString($searchString)
	{
		$searchString=ucfirst(strtolower($searchString));
		$searchWords  = explode(' ', $searchString);
		if (count($searchWords) === 1)
		{
			return '*' . $searchString . '*';
		}
		$preparedSearchString = $searchWords[0] . '*';
		unset($searchWords[0]);
		foreach ($searchWords as $searchWord)
		{
			$preparedSearchString .= '+AND+'. $searchWord;
		}
		$preparedSearchString = str_replace(' ', '+%2B', $preparedSearchString);
		return $preparedSearchString;
	}


	private function callNbnApi($queryUrl)
	{
		$nbnApiResponse = new NbnApiResponse();
		try
		{
			//setting timeout to five seconds as
			ini_set('default_socket_timeout', self::API_TIMEOUT);
			$jsonResults  = file_get_contents($queryUrl);
			$jsonResponse = json_decode($jsonResults);

			if (isset($jsonResponse->status) &&  $jsonResponse->status === 'ERROR')
			{
				$nbnApiResponse->status  = 'ERROR';
				$errorMessage = $jsonResponse->errorMessage;
				if (strPos($errorMessage, 'No live SolrServers available') !== false)
				{
					$errorMessage = '<b>The NBN API is currently not able to provide results.</b>';
				}
				$nbnApiResponse->message = $errorMessage;
				$nbnApiResponse->jsonResponse = [];
			}
			else
			{
				$nbnApiResponse->jsonResponse = $jsonResponse;
				$nbnApiResponse->status       = 'OK';
			}
		}
		catch (\Throwable $e)
		{
			$nbnApiResponse->status = 'ERROR';
			$errorMessage           = $e->getMessage();
			if (strpos($errorMessage, '400 Bad Request') !== false)
			{
				$errorMessage = '<b>It looks like there is a problem with the query.</b>  Here are the details: ' . $errorMessage;
			}
			if (strpos($errorMessage, '500') !== false||strpos($errorMessage, '503') !== false ||strpos($errorMessage, 'php_network_getaddresses') !== false||strpos($errorMessage, 'SSL') !== false||strpos($errorMessage, 'stream') !== false)
			{
				$errorMessage = '<b>It looks like there is a problem with the NBN API</b>.  Here are the details: ' . $errorMessage;
			}
			$nbnApiResponse->message = $errorMessage;
		}
		return $nbnApiResponse;
	}
}
