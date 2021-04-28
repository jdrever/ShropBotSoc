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
		$this->data['site_name'] = "Shropshire";
		$this->data['title']     = urldecode($speciesName);
		$this->data['speciesName']   = $this->request->getVar('name', FILTER_SANITIZE_ENCODED) ?? $speciesName;
		$records                     = $this->nbn->getSingleSpeciesRecordsForCounty($speciesName, $this->page);
		$this->data['status']        = $records->status;
		$this->data['message']       = $records->message;
		$this->data['download_link'] = $records->downloadLink;
		$this->data['recordsList']   = $records->records;
    	$this->data['sites']         = $records->sites;
		$this->data['page']          = $this->page;
		$this->data['queryUrl']      = $records->queryUrl;
		$this->data['totalRecords']  = $records->totalRecords;
		$this->data['totalPages']    = $records->getTotalPages();
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

		$records= $this->nbn->getSingleSpeciesRecordsForSite($siteName, $speciesName, $this->page);
		$this->data['recordsList'] = $records->records;
		$this->data['page']          = $this->page;
		$this->data['queryUrl']      = $records->queryUrl;
		$this->data['totalRecords']  = $records->totalRecords;
		$this->data['totalPages']    = $records->getTotalPages();
		echo view('site_species_records', $this->data);
	}

	public function singleSpeciesForSquare($gridSquare, $speciesName)
	{
		// Map of site
		$this->data['gridSquare']    = $gridSquare;
		$this->data['speciesName'] = $speciesName;

		$records= $this->nbn->getSingleSpeciesRecordsForSquare($gridSquare, $speciesName, $this->page);
		$this->data['recordsList'] = $records->records;
		$this->data['page']          = $this->page;
		$this->data['queryUrl']      = $records->queryUrl;
		$this->data['totalRecords']  = $records->totalRecords;
		$this->data['totalPages']    = $records->getTotalPages();
		$this->data['status']        = $records->status;
		$this->data['message']       = $records->message;
		echo view('square_species_records', $this->data);
	}

	/**
	 * Display a single record
	 */
	public function singleRecord($uuid)
	{
		$record                       = $this->nbn->getSingleOccurenceRecord($uuid);
		$occurrence                   = $record->records->processed->occurrence;

		$this->data['occurrence']     = $occurrence;
		$classification               = $record->records->processed->classification;
		$this->data['classification'] = $classification;
		$title                        = $classification->scientificName . "-" . $occurrence->recordedBy;
		$this->data['location']       = $record->records->raw->location; # `raw` contains the locationID
		$this->data['event']          = $record->records->processed->event;
		$this->data['title']          = $title;
		$this->data['recordId']       = $record->records->processed->rowKey;
		$this->data['status']        = $record->status;
		$this->data['message']       = $record->message;
		//NOTE: the NBN API currently doesn't support a CSV download for
		//detailed occurance records
		//$this->data['downloadLink']   = $record->downloadLink;
		echo view('single_record', $this->data);
	}
}
