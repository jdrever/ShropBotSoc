<?php namespace App\Controllers;

class Species extends BaseController
{
    private $data = array('title' => 'Species');

    /**
     * Search the records for a species name or part there of
     */
    public function index()
    {
        // Set a default search type
        if ($this->isPostBack()) 
        {
            $this->data['nameType'] = $this->request->getVar('name-type');
        }
        else
        {
            // Don't show anything
            // $this->data['title'] = $this->data['title']." - results";
            // // Make sure the posted fields are set back
            // $name_search_string = $this->request->getVar('search');
            // $this->data['search'] = $name_search_string;
            // $name_type = $this->request->getVar('in');
            // $this->data['in'] = $name_type;
            // // Search for the species
            // $this->data['taxa'] = $this->nbnModel->getTaxa($name_search_string, $name_type);
        };
        echo view('species_search', $this->data);
    }

    /**
     * List the records
     */
    public function records($speciesName)
    {
        $this->data['title'] = urldecode($speciesName);
        $this->data['speciesName'] = $speciesName;
        $this->data['records'] = $this->nbnModel->getRecords($speciesName);
        echo view('species_records', $this->data);
    }

}
