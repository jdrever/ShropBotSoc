<?php
class Records extends MY_Controller
{

    /**
     * Loading handy libraries, nothing else.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('records_model');
        $this->load->model('shrops_model'); // can this be removed yet?
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
        $data['groups'] = $this->shrops_model->get_groups(); //For the drop-down list of groups on the form
        $data['main_content'] = 'shrops/start';
        $this->load->view('layout', $data);
    }

    /**
     * Speices lists
     * https://stackoverflow.com/questions/3675135/codeigniter-best-way-to-structure-partial-views
     * 
     * Maybe this for errors
     * https://itsolutionstuff.com/post/how-to-implement-flash-messages-in-php-codeigniterexample.html
     */
    public function species()
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

    /**
     * 
     */
    public function tetradmap()
    {
        $get = $this->uri->uri_to_assoc();
        $data['speciesname'] = $this->shrops_model->get_species_name($get['species']);

        $data['main_content'] = 'shrops/map';
        $this->load->view('layout', $data);
    } 

    public function records_for_map_tetrad()
    {
        //When a dot on the species tetrad map is clicked
        $get = $this->uri->uri_to_assoc();
        $map_x = $this->input->post('map_x');
        $map_y = $this->input->post('map_y');
        $tetrad = get_tetrad_from_map($map_x, $map_y);
        //echo "The x coordinate is " . $map_x . "and the y coordinate is " . $map_y ."</br>";
        //echo "The tetrad is " . $tetrad ."</br>";
        // echo "The species id is " . $get['species'];
        $data['thespecies'] = $this->shrops_model->get_species_name($get['species']);
        $data["thetetrad"] = $tetrad;
        $data['tetrecsforspp'] = $this->shrops_model->get_tetrecs_for_species($get['species'], $tetrad);
        $data['main_content'] = 'shrops/tetrad-records-for-species';
        $this->load->view('layout', $data);
    } //End of function records_for_tetrads()

    public function findtetradsfrommap()
    {

        $data['main_content'] = 'shrops/map-find-tetrads';
        $this->load->view('layout', $data);
    } // End of function findtetradsfrommap()

    public function all_records_for_map_tetrad()
    {
        //When an area on the general tetrad map is clicked
        $get = $this->uri->uri_to_assoc();
        $map_x = $this->input->post('map_x');
        $map_y = $this->input->post('map_y');
        $tetrad = get_tetrad_from_map($map_x, $map_y);
        //Make the pagination array
        $config = array();
        $config['base_url'] = base_url('/index.php/shrops/showrecords/tetrads/' . $tetrad . '/offset');
        $config['total_rows'] = $this->shrops_model->get_rec_count_tetrads($tetrad);
        $config['per_page'] = 20;
        $config["uri_segment"] = 6;
        $config['num_links'] = 10;

        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = '&gt;&gt;';
        $config['prev_link'] = '&lt;&lt;';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;

        // $choice = $config["total_rows"] / $config["per_page"];
        // $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;

        $data['tetrads'] = $tetrad;
        $results = array();
        $results = $this->shrops_model->get_records_for_tetrads($tetrad, $config['per_page'], $page);
        $data['the_records'] = $results['main'];
        $data['csv_qry_str'] = $results['csvqry'];

        //You can put what you like instead of 'the_records' as long as this is
        //what you pick up in the View -
        $data['total_rows'] = $config['total_rows'];
        $data['links'] = $this->pagination->create_links();
        $data['main_content'] = 'shrops/records-for-tetrads';
        $this->load->view('layout', $data);
    } //End of function records_for_tetrads()

    public function makemap()
    {
        //Creates the map image for the img src in the map.php view
        $get = $this->uri->uri_to_assoc();
        $coords = $this->shrops_model->get_tetrads_for_species($get['sppid']);
        $countxy = count($coords['x']);

        // $im = ImageCreateFromPNG(base_url(). "assets/images/shrops.png");
        $im = ImageCreateFromPNG(base_url() . "assets/images/SEDN-base-map-wood-2.png");
        $maroon = ImageColorAllocate($im, 100, 0, 0);
        $black = ImageColorAllocate($im, 0, 0, 0);
        $green = ImageColorAllocate($im, 0, 100, 0);
        $lime = ImageColorAllocate($im, 159, 251, 77);
        $paleblue = ImageColorAllocate($im, 228, 248, 244);
        $white = ImageColorAllocate($im, 255, 255, 255);

        for ($i = 0; $i <= $countxy - 1; $i++) {
            $thisx = $coords['x'][$i];
            $thisy = $coords['y'][$i];
            $year = $coords['year'][$i];
            if ($year < 1985) {
                ImageArc($im, $thisx, $thisy, 14, 14, 0, 360, $black);
                ImageFillToBorder($im, $thisx, $thisy, $black, $paleblue);
            } else {
                ImageArc($im, $thisx, $thisy, 14, 14, 0, 360, $black);
                ImageFillToBorder($im, $thisx, $thisy, $black, $green);
            }
        }

        ImagePNG($im);
        ImageDestroy($im);

    }

    public function make_base_map()
    {
        //Creates the base map image on which to click to return a tetrad

        $im = ImageCreateFromPNG(base_url() . "assets/images/SEDN-base-map-wood-2.png");

        ImagePNG($im);
        ImageDestroy($im);

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
