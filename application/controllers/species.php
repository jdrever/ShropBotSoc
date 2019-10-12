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
            // $this->search($speciesName, $axiophytesOnlyCheck, $commonNamesCheck);
        }
        else // not a post back
        {
            $this->data['taxa'] = $this->nbn_model->getTaxa();
        };
        $this->load->view('species/search', $this->data);
    }

    /**
     * Get a list of species - either all species, names based on search string, or
     * names beginning with a specified letter. Send them for display in a table
     */
    public function search($speciesName, $axiophytesOnlyCheck, $commonNamesCheck)
    {


        if (isset($speciesName))
        //This script is accessed from one of the alphabetic links
        {
            $name_type = $get['name_type'];
            $letter = $get['letter'];
            $group = $get['group'];
            $data['axio'] = $get['axio'];
            $data['name_type'] = $name_type;
            $data['taxon_group'] = $group;
            $data['alpha_links'] = true;
            $data['species_list'] = $this->shrops_model->get_species($name_type, $letter, $group, 0, $get['axio']);
        } 
        else 
        {
            $letter = 'NONE';
            if ($this->input->post('all-species') == 'ischecked')
            //We've come here from the start form and all species checkbox is checked
            {
                //Determine if Common name is selected
                if ($this->input->post('search_common') == 'ischecked') 
                {
                    $name_type = 'sppCommon';
                } 
                else 
                {
                    $name_type = 'sppName';
                }

                //Determine if axiophyte is selected
                if ($this->input->post('axiosonly') == 'ischecked') {
                    $axio = '1';
                } else {
                    $axio = '0';
                }

                //This is where you capture axio from the
                //The value of $axio has to be a string since sspAxio is type 'enum'

                $data['axio'] = $axio;
                $data['name_type'] = $name_type;
                $data['alpha_links'] = true;
                $data['taxon_group'] = $this->input->post('taxon-group');
                $data['species_list'] = $this->shrops_model->get_species($name_type, 'A', $this->input->post('taxon-group'), 1, $axio);
            } elseif ($this->input->post('speciesname') !== '') //We've come here from the start form and a species name string was entered
            {
                if ($this->input->post('search_common') == 'ischecked') {
                    $name_type = 'sppCommon';
                } else {
                    $name_type = 'sppName';
                }

                $data['axio'] = $axio;
                $data['name_type'] = $name_type;
                $data['alpha_links'] = false; //We've entered a name fragment - we don't need the alpha links
                $data['taxon_group'] = $this->input->post('taxon-group');
                $data['species_list'] = $this->shrops_model->get_species($name_type, $this->input->post('speciesname'), $this->input->post('taxon-group'), 2, $axio);
            }

            /*Once you've added everything else you need to $data
            specify that the shrops/species View goes into 'main_content' and then
            load that into the template View */
            $data['groups'] = $this->shrops_model->get_groups();
            //what we're doing here is sending a full list of key|value paires
            //for groups so that in the shrops/species view the key in $taxon_group
            //can be used to get the group name. Is theer a better way?
            $data['main_content'] = 'shrops/species';
            $this->load->view('layout', $data);
        }
    } //End of function species()

    /**
     * In the event of an error post a message on the landing page.
     */
    public function index_error($error, $type)
    {
        $data['error'] = $error;
        $data['type'] = $type; //Is it a species or sites error?
        $data['groups'] = $this->shrops_model->get_groups(); //For the drop-down list of groups on the form
        $data['main_content'] = 'shrops/start';
        $this->load->view('layout', $data);
    }

}
