<?php namespace App\Controllers;

/**
 * Manage the records views.
 */
class Records extends BaseController
{
    private $data = array('title' => 'Records');

    /**
     * List the records for a species in the entire dataset
     */
    public function forASpeciesInDataset($speciesName)
    {
        $this->data['title'] = urldecode($speciesName);
        $this->data['speciesName'] = $speciesName;
        $this->data['records'] = $this->nbn->getRecordsForASpecies($speciesName);
        echo view('species_records', $this->data);
    }

    /**
     * 
     */
    public function forASpeciesInASite($siteId, $speciesName)
    {
        // Mapp of site
        $this->data['records'] = $this->nbn->getSiteSpeciesList($siteId, $speciesName);
        echo view('species_records', $this->data);
    }

    /**
     * Display a single record
     */
    public function singleRecord($uuid)
    {
        $record = $this->nbn->getRecord($uuid);
        $occurrence = $record->processed->occurrence;
        $this->data['occurrence'] = $occurrence;
        $classification = $record->processed->classification;
        $this->data['classification'] = $classification;
        $title = $classification->scientificName."-".$occurrence->recordedBy;
        $this->data['location'] = $record->raw->location; # `raw` contains the locationID
        $this->data['event'] = $record->processed->event;
        $this->data['title'] = $title;
        echo view('single_record', $this->data);
    }
}