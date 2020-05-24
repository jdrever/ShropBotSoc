<?php
class Tetrads extends MY_Controller
{
    
    /**
     * Loading handy libraries, nothing else.
     */
    public function __construct()
    {
        parent::__construct();
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
     * Landing page showing the tetrads search form.
     */
    public function index()
    {
        $data = array('title' => 'Tetrads');
        $this->load->view('tetrads/search', $data);
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

}