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
    public function singleSpeciesForCounty($species_name)
    {
        $this->data['site_name'] = "Shropshire";
        $this->data['title'] = urldecode($species_name);
        $this->data['species_name'] = $species_name;
        $records = $this->nbn->getSingleSpeciesRecordsForCounty($species_name);
        $this->data['download_link'] = $records['download_link'];
        $this->data['records_list'] = $records['records_list'];
        echo view('species_records', $this->data);
    }

    /**
     * Display records for a single species for a site
     */
    public function singleSpeciesForSite($site_name, $species_name)
    {
        // Map of site
        $this->data['site_name'] = $site_name;
        $this->data['species_name'] = $species_name;
        $this->data['records_list'] = $this->nbn->getSingleSpeciesRecordsForSite($site_name, $species_name);
        echo view('species_records', $this->data);
    }

    /**
     * Display a single record
     */
    public function singleRecord($uuid)
    {
        $record = $this->nbn->getSingleOccurenceRecord($uuid);
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