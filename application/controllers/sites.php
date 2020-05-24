<?php
class Sites extends MY_Controller
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
     * Landing page showing the sites search form.
     */
    public function index()
    {
        $data = array('title' => 'Sites');
        $this->load->view('sites/search', $data);
    }
}