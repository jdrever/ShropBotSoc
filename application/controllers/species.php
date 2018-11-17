<?php
class Species extends MY_Controller
{
    /**
     * Loading handy libraries, nothing else.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('records_model');
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
     * Get the drop load list of species groups and present the
     * landing page.
     */
    public function index()
    {
        $data = array('title' => 'Species');
        $data['groups'] = $this->records_model->get_groups();
        $this->load->view('species/search', $data);
    }

    /**
     * Search the records for a species name or part there of 
     */
    public function search()
    {
        $data = array('title' => 'Species');
        $data['groups'] = $this->records_model->get_groups();
        if ($this->isPostBack()) // post back
        {
            $data['title'] = $data['title']." - results";
            $data['error'] = "This is a post back";

        }
        else // not a post back
        {
            
        }
        $this->load->view('records/species', $data);
    } 

    public function sites()
    {
        //Get a list of sites - either all sites, names based on search string, or
        //names beginning with a specified letter. Send them for display in a table
        $get = $this->uri->uri_to_assoc();
        //check something has been sent
        if (!isset($get['letter'])
            and $this->input->post('all-sites') !== 'ischecked' and $this->input->post('sitename') == '') {
            $error_message = 'ERROR - you must enter a site name or check the box to browse all sites';
            $this->index_error($error_message, 'sites');
        } else {

            if (isset($get['letter']))
            //This script is accessed from one of the alphabetic links
            {
                $site_name = $get['letter'];

                $data['alpha_links'] = true;
                $data['sites_list'] = $this->shrops_model->get_sites($site_name, 0);
            } else {
                $letter = 'NONE';
                if ($this->input->post('all-sites') == 'ischecked')
                //We've come here from the start form and all sites checkbox is checked
                {
                    $data['alpha_links'] = true;
                    $data['sites_list'] = $this->shrops_model->get_sites($this->input->post('site'), 1);
                } elseif ($this->input->post('sitename') !== '')
                //We've come here from the start form and a site name string was entered
                {

                    $data['alpha_links'] = false;

                    $data['sites_list'] = $this->shrops_model->get_sites($this->input->post('sitename'), 2);
                }
            }

            /*Once you've added everyting else you need to $data
            specify that the shrops/sites View goes into 'main_content' and then
            load that into the template View */

            $data['main_content'] = 'shrops/sites';
            $this->load->view('layout', $data);
        }
    } //End of function sites()

    public function tetrads()
    {
        //Get a list of tetrads - either all tetrads, names based on search string, or
        //names beginning with a specified letter. Send them for display in a table
        $this->load->library('pagination');

        if ($this->input->post())
        //The script has been accessed from the stsrt_page form
        {
            //check something has been sent and return an error if not
            if ($this->input->post('all-tetrads') !== 'ischecked' and $this->input->post('tetradname') == '') {
                $error_message = 'ERROR - you must enter a tetrad name or check the box to browse all tetrads';
                $this->index_error($error_message, 'tetrads');
            } else {

                if ($this->input->post('all-tetrads') == 'ischecked')
                //We've come here from the start form and all tetrads checkbox is checked
                {

                    $config = array();
                    $config['base_url'] = base_url('/index.php/shrops/tetrads/' . $this->input->post('tetrad') . '/offset');
                    $config['total_rows'] = 991;
                    $config['per_page'] = 20;
                    $config["uri_segment"] = 5;
                    $config['num_links'] = 10;

                    $config['first_link'] = 'First';
                    $config['last_link'] = 'Last';
                    $config['next_link'] = '&gt;&gt;';
                    $config['prev_link'] = '&lt;&lt;';

                    $this->pagination->initialize($config);
                    $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;

                    $data['total_rows'] = $config['total_rows'];
                    $data['links'] = $this->pagination->create_links();

                    $data['tetrad_header'] = "All Tetrads in Shropshire";
                    $data['tetrads'] = $this->shrops_model->get_tetrads($this->input->post('tetrad'), $config['per_page'], $page);

                } elseif ($this->input->post('tetradname') !== '')
                //We've come here from the start form and a tetrad name string was entered
                {

                    $data['tetrad_header'] = "Tetrads Matching Your Search Term";
                    $data['tetrads'] = $this->shrops_model->get_tetrads($this->input->post('tetradname'), 1000, 0);
                }

                /*Once you've added everyting else you need to $data
                specify that the shrops/sites View goes into 'main_content' and then
                load that into the template View */

                $data['main_content'] = 'shrops/tetrads';
                $this->load->view('layout', $data);

            }

        } //End of dealing with input from start_page form
        else {

            //Do when the scrip is accessed from a pagination link
            $get = $this->uri->uri_to_assoc();
            //echo $get['offset'];
            $all_tetrads = "";
            $config = array();
            $config['base_url'] = base_url('/index.php/shrops/tetrads/' . $this->input->post('tetrad') . '/offset');
            $config['total_rows'] = 991;
            $config['per_page'] = 20;
            $config["uri_segment"] = 5;
            $config['num_links'] = 10;

            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['next_link'] = '&gt;&gt;';
            $config['prev_link'] = '&lt;&lt;';

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;

            $data['total_rows'] = $config['total_rows'];
            $data['links'] = $this->pagination->create_links();

            $data['tetrad_header'] = "All Tetrads in Shropshire";
            $data['tetrads'] = $this->shrops_model->get_tetrads($all_tetrads, $config['per_page'], $page);
            $data['main_content'] = 'shrops/tetrads';
            $this->load->view('layout', $data);
        }

    } //End of function tetrads()

    public function get_csv()
    {
        //Called from forms on pages displaying tables of records
        //Generates a csv and forces download of it

        //Strip the LIMIT clause from the query, which is just there for
        //pagination purposes.
        $csvquery = striplimit($this->input->post('csv_qry_str'));

        //Run the query

        $csvresult = $this->shrops_model->run_db_query($csvquery);

        //Extract a csv from the query and asssign to a variable
        $delimiter = "|";
        $csv = $this->dbutil->csv_from_result($csvresult, $delimiter);

        //Force download of the csv
        force_download('sedndata.csv', $csv);

    }
}
