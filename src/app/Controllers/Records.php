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
	public function singleSpeciesForSite($site_name, $Nme)
	{
		// Map of site
		$this->data['site_name']    = $site_name;
		$this->data['Nme']          = $Nme;
		$this->data['records_list'] = $this->nbn->getSingleSpeciesRecordsForSite($site_name, $Nme);
		echo view('species_records', $this->data);
	}

	/**
	 * Display a single record
	 */
	public function singleRecord($uuid)
	{
		$record                       = $this->nbn->getSingleOccurenceRecord($uuid);
		$occurrence                   = $record->processed->occurrence;
		$this->data['occurrence']     = $occurrence;
		$classification               = $record->processed->classification;
		$this->data['classification'] = $classification;
		$title                        = $classification->scientificName . "-" . $occurrence->recordedBy;
		$this->data['location']       = $record->raw->location; # `raw` contains the locationID
		$this->data['event']          = $record->processed->event;
		$this->data['title']          = $title;
		echo view('single_record', $this->data);
	}
}
