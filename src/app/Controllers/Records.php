<?php namespace App\Controllers;

/**
 * Manage the records views.
 * 
 * TODO: Caching
 */
class Records extends BaseController
{
    private $data = array('title' => 'Records');

    /**
     * Landing page 
     * 
     * TODO Search records by collector?
     */
    public function index()
    {
        echo view('records_search', $this->data);
    }

    /**
     * Display a single record
     */
    public function singleRecord($uuid)
    {
        $record = $this->nbnModel->getRecord($uuid);
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