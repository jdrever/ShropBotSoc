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
            return redirect()->to("/species/{$name_search_string}/group/{$species_group}/type/{$name_type}");
        };
        $this->data['searchString'] = "";
        echo view('species_search', $this->data);
    }

    /**
     * Return the species list for the county
     */
    public function listForCounty($name_search_string, $species_group, $name_type)
    {
        $this->data['title'] = $this->data['title']." - ".$name_search_string;
        $this->data['searchString'] = $name_search_string;
        $this->data['speciesList'] = $this->nbn->getSpeciesListForCounty($name_search_string, $name_type, $species_group);
        echo view('species_search', $this->data);
    }

    /**
     * Return the species list for a named site.
     */
    public function listForSite($site_name, $species_group, $name_type)
    {
        $this->data['title'] = urldecode($site_name);
        $species_group = $this->request->getVar('species-group');
        $this->data['speciesList'] = $this->nbn->getSpeciesListForSite($site_name, $species_group);
        echo view('site_species_list', $this->data);
    }

    /**
     * Return the species list for a square.
     */
    public function listforSquare($square, $species_group, $name_type)
    {
        echo view('square_species_list', $this->data);
    }
}
