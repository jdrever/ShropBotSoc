<?php namespace App\Controllers;

/**
 * Manage the species or taxa views.
 * 
 * TODO: Caching
 */
class Species extends BaseController
{
    private $data = array('title' => 'Species');

    /**
     * List the species in the dataset
     */
    public function index()
    {
        if ($this->isPostBack()) 
        {
            $this->data['title'] = $this->data['title']." - results";
            $name_search_string = $this->request->getVar('search');
            $name_type= $this->request->getVar('name-type');
            $this->data['taxa'] = $this->nbnModel->getSpeciesInDataset($name_search_string, $name_type);
        };
        echo view('species_search', $this->data);
    }

    /**
     * Get the species count for a site
     */
    public function speciesForSite($siteName)
    {
        $this->data['title'] = urldecode($siteName);
        $this->data['speciesList'] = $this->nbnModel->getSiteSpeciesList($siteName);
        echo view('site_species_list', $this->data);
    }

    /**
     * List the records for a species in the dataset
     */
    public function recordsInDataset($speciesName)
    {
        $this->data['title'] = urldecode($speciesName);
        $this->data['speciesName'] = $speciesName;
        $this->data['records'] = $this->nbnModel->getRecordsForASpecies($speciesName);
        echo view('species_records', $this->data);
    }

}
