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
            $name_search_string = trim($name_search_string);
            // If the search field is empty, go to the begining of the alphabet
            if (trim($name_search_string) == NULL) $name_search_string = "A";
            $name_type = $this->request->getVar('name-type');
            $species_group = $this->request->getVar('species-group');
            $this->data['taxa'] = $this->nbn->getSpeciesListForCounty($name_search_string, $name_type, $species_group);
        };
        echo view('species_search', $this->data);
    }

    /**
     * Return the species list for a named site.
     */
    public function speciesForSite($site_name)
    {
        $this->data['title'] = urldecode($site_name);
        $species_group = $this->request->getVar('species-group');
        $this->data['speciesList'] = $this->nbn->getSpeciesListForSite($site_name, $species_group);
        echo view('site_species_list', $this->data);
    }
}
