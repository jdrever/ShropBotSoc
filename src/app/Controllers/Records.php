<?php

namespace App\Controllers;

/**
 * Manage the records views.
 */
class Records extends BaseController
{
	private $data = ['title' => 'Records'];

	/**
	 * List the records for a species in the entire dataset
	 */
	public function singleSpeciesForCounty($speciesName)
	{
		$this->data['site_name']        = "Shropshire";
		$this->data['speciesName']      = urldecode($speciesName);
		$this->data['displayName']      = $this->request->getVar('displayName', FILTER_SANITIZE_ENCODED) ?? $speciesName;
		//$this->data['nameSearchString'] = $this->request->getVar('nameSearchString', FILTER_SANITIZE_ENCODED);
		$records                        = $this->nbn->getSingleSpeciesRecordsForCounty($speciesName, $this->page);
		$this->data['status']           = $records->status;
		$this->data['message']          = $records->message;
		$this->data['download_link']    = $records->downloadLink;
		$this->data['recordsList']      = $records->records;
		$this->data['sites']            = $records->sites;
		$this->data['page']             = $this->page;
		$this->data['queryUrl']         = $records->queryUrl;
		$this->data['totalRecords']     = $records->totalRecords;
		$this->data['speciesGuid']		= isset($records->records[0]->speciesGuid) ? $records->records[0]->speciesGuid : '';

		$this->data['totalPages']       = $records->getTotalPages();

		$speciesNameSearch=get_cookie("speciesNameSearch");
		if (empty($speciesNameSearch))
			$speciesNameSearch=$speciesName;
		$this->data['speciesNameSearch']	= $speciesNameSearch;

		$this->data['speciesNameType'] = !empty(get_cookie('nameType')) ? get_cookie('nameType') : 'scientific' ;
		$this->data['speciesGroup'] = !empty(get_cookie('speciesGroup')) ? get_cookie('speciesGroup') : 'both' ;
		$this->data['axiophyteFilter'] = !empty(get_cookie('axiophyteFilter')) ? get_cookie('axiophyteFilter') : 'false' ;
		echo view('species_records', $this->data);
	}

	/**
	 * Display records for a single species for a site
	 */
	public function singleSpeciesForSite($siteName, $speciesName)
	{
		// Map of site
		$this->data['siteName']    = $siteName;
		$this->data['speciesName'] = $speciesName;

		$records                    = $this->nbn->getSingleSpeciesRecordsForSite($siteName, $speciesName, $this->page);
		$this->data['recordsList']  = $records->records;
		$this->data['page']         = $this->page;
		$this->data['queryUrl']     = $records->queryUrl;
		$this->data['totalRecords'] = $records->totalRecords;
		$this->data['totalPages']   = $records->getTotalPages();
		$this->data['siteLocation']	= reset($records->sites);

		$this->data['speciesNameType'] = !empty(get_cookie('nameType')) ? get_cookie('nameType') : 'scientific' ;
		$this->data['speciesGroup'] = !empty(get_cookie('speciesGroup')) ? get_cookie('speciesGroup') : 'both' ;



		echo view('site_species_records', $this->data);
	}

	public function singleSpeciesForSquare($gridSquare, $speciesName)
	{
		// Get a 6 digit grid reference (1km square) from any length of original
		// grid reference by finding the midpoint of the position splitting the
		// string.
		$gsSplitPoint = strlen($gridSquare) / 2 + 1;
		$gridSquare = substr($gridSquare, 0, 4) . substr($gridSquare, $gsSplitPoint, 2);

		// Map of site
		$this->data['gridSquare']  = $gridSquare;
		$this->data['speciesName'] = $speciesName;
		$records                    = $this->nbn->getSingleSpeciesRecordsForSquare($gridSquare, $speciesName, $this->page);
		$this->data['recordsList']  = $records->records;
		$this->data['page']         = $this->page;
		$this->data['queryUrl']     = $records->queryUrl;
		$this->data['totalRecords'] = $records->totalRecords;
		$this->data['totalPages']   = $records->getTotalPages();
		$this->data['status']       = $records->status;
		$this->data['message']      = $records->message;
		echo view('square_species_records', $this->data);
	}

	/**
	 * Display a single record
	 */
	public function singleRecord($uuid)
	{
		$record = $this->nbn->getSingleOccurenceRecord($uuid);

		if ($record->status === 'OK')
		{
			$occurrence               = $record->records->processed->occurrence;
			$this->data['occurrence'] = $occurrence;

			// Sort out and separate recorder name pairs with a semi-colon
			$recorders    = explode("|", $this->data['occurrence']->recordedBy);
			$newRecorders = [];
			foreach ($recorders as $key => $value)
			{
				// Stick a semicolon between every other name pair
				if ($key !== 0 && $key % 2 === 0)
				{
					array_push($newRecorders, '; ');
				}
				array_push($newRecorders, $value);
			}
			$this->data['occurrence']->recordedBy = implode($newRecorders);

			$classification               = $record->records->processed->classification;
			$this->data['classification'] = $classification;
			$displayName                  = $this->request->getVar('displayName', FILTER_SANITIZE_ENCODED) ?? $classification->scientificName;
			$location                     = $record->records->raw->location; # `raw` contains the locationID;
			$gridReference				  = "Unknown grid reference";
			if (isset($location->gridReference))
			{
				$gridReference=$location->gridReference;
			}

			$displayTitle                 = 'Record detail for ' . urldecode($displayName) . ' recorded by ' . $occurrence->recordedBy . ' at ' . $location->locationID . ' (' .$gridReference . '),' . $record->records->processed->event->year . '.';
			$this->data['location']       = $location;
			$this->data['event']          = $record->records->processed->event;
			$this->data['displayName']    = $displayName;
			$this->data['title']          = $displayTitle;
			$this->data['gridReference']    = $gridReference;
			$fullDate='Not available';
			if (isset($record->records->processed->event->eventDate))
				$fullDate =date_format(date_create($record->records->processed->event->eventDate),'jS F Y') ;
			$this->data['fullDate'] = $fullDate;

			$this->data['queryUrl']       = $record->queryUrl;

			$this->data['recordId']       = $record->records->processed->rowKey;

			$this->data['download_link']    = $record->downloadLink;
		}
		$this->data['status']  = $record->status;
		$this->data['message'] = $record->message;

		//NOTE: the NBN API currently doesn't support a CSV download for
		//detailed occurance records
		//$this->data['downloadLink']   = $record->downloadLink;
		echo view('single_record', $this->data);
	}
}
