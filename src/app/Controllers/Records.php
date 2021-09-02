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
		$this->data['totalPages']       = $records->getTotalPages();

		$speciesNameSearch=get_cookie("speciesNameSearch");
		if (empty($speciesNameSearch))
			$speciesNameSearch=$speciesName;
		$this->data['speciesNameSearch']	= $speciesNameSearch;

		$this->data['speciesNameType'] = !empty(get_cookie('nameType')) ? get_cookie('nameType') : 'scientific' ;
		$this->data['speciesGroup'] = !empty(get_cookie('speciesGroup')) ? get_cookie('speciesGroup') : 'both' ;

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
		echo view('site_species_records', $this->data);
	}

	public function singleSpeciesForSquare($gridSquare, $speciesName)
	{
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
			$displayTitle                 = 'Record detail for ' . urldecode($displayName) . ' recorded by ' . $occurrence->recordedBy . ' at ' . $location->locationID . ' (' . $record->records->processed->event->year . ')';
			$this->data['location']       = $location;
			$this->data['event']          = $record->records->processed->event;
			$this->data['displayName']    = $displayName;
			$this->data['title']          = $displayTitle;

			$fullDate='Not available';
			if (isset($record->records->processed->event->eventDate))
				$fullDate =date_format(date_create($record->records->processed->event->eventDate),'jS F Y') ;
			$this->data['fullDate'] = $fullDate;

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
