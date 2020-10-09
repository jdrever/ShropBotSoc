<?php namespace App\Controllers;

class Species extends BaseController
{
    private $data = array('title' => 'Species');

    /**
     * Get the drop load list of species groups and present the species form
     * or
     * search the records for a species name or part there of
     */
    public function index()
    {
        if ($this->isPostBack()) // post back
        {
            $this->data['title'] = $this->data['title']." - results";
            // Make sure the posted fields are set back
            $speciesName = $this->input->post('species-name');
            $this->data['speciesName'] = $speciesName;
            $axiophytesOnlyCheck = $this->input->post('axiophytes-only-check');
            $this->data['axiophytesOnlyCheck'] = $axiophytesOnlyCheck;
            $commonNamesCheck = $this->input->post('common-names-check');
            $this->data['commonNamesCheck'] = $commonNamesCheck;
            $groupSelected = $this->input->post('taxon-group'); 
            $this->data['groupSelected'] = $groupSelected;
            // Search for the species
            $this->data['taxa'] = $this->nbn_model->getTaxa($speciesName, $groupSelected);
        }
        else // not a post back but the first viewing
        {
            $this->data['taxa'] = $this->nbnModel->getTaxa('A', 'Plants');
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
        $this->data['records'] = $this->nbn_model->getRecords($speciesName);
        $this->load->view('species/records', $this->data);
    }

}
