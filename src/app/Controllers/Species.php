<?php namespace App\Controllers;

/**
 * Manage the species or taxa views.
 */
class Species extends BaseController
{
    private $data = array('title' => 'Species');

    /**
     * If it is a post back return a search of the species listed in the dataset
     */
    public function index()
    {
        if ($this->isPostBack()) 
        { 
            $this->data['title'] = $this->data['title']." - results";
            $name_search_string = $this->request->getVar('search');
            // If the search field is empty, go to the begining of the alphabet
            if (IsNullOrEmptyString($name_search_string)) $name_search_string = "A";
            $name_type= $this->request->getVar('name-type');
            $this->data['taxa'] = $this->nbn->getSpeciesInDataset($name_search_string, $name_type);
        };
        echo view('species_search', $this->data);
    }

    /**
     * Return the species list for a named site.
     */
    public function speciesForSite($siteName)
    {
        $this->data['title'] = urldecode($siteName);
        $this->data['speciesList'] = $this->nbn->getSiteSpeciesList($siteName);
        echo view('site_species_list', $this->data);
    }
}
