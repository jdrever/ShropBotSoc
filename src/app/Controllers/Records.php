<?php namespace App\Controllers;

class Records extends BaseController
{
    private $data = array('title' => 'Squares');

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
    public function singleRecord($record_id)
    {
        echo view('record', $this->data);
    }

 
}