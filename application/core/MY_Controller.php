<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function _output($content)
    {
        // Load the base template with output content available as $content
        $data['content'] = &$content;
        echo($this->load->view('bootstrap_layout', $data, true));
    }

    function isPostBack()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

}