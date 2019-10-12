<?php
class Species extends MY_Controller
{
    private $data = array('title' => 'Species');

    /**
     * Loading handy libraries, nothing else.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('shrops_model');
        $this->load->model('nbn_model');
        $this->load->library('table'); // for constructing HTML tables
        $this->load->library('pagination');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('map');
        $this->load->helper('table');
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
    }

    /**
     * Get the drop load list of species groups and present the species form
     * or
     * search the records for a species name or part there of
     */
    public function index()
    {
        $this->data['groups'] = $this->nbn_model->getGroups();
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
            $this->data['taxa'] = $this->nbn_model->getTaxa('A', 'ALL_SPECIES');
        };
        $this->load->view('species/search', $this->data);
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
