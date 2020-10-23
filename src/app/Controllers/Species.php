<?php namespace App\Controllers;

class Species extends BaseController
{
    private $data = array('title' => 'Species');

    /**
     * Search the records for a species name or part there of
     */
    public function index()
    {
        if ($this->isPostBack()) 
        {
            $name_search_string = $this->request->getVar('search');
            $name_type= $this->request->getVar('name-type');
            $this->data['taxa'] = $this->nbnModel->getTaxa($name_search_string, $name_type);
        }
        else
        {
            // Don't show anything
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
