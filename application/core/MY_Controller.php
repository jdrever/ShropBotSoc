<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Customized controller for this application.
 */
class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Load the base template with output content available as $content,
     * which essentially means that all the views have a parent view of
     * bootstrap_layout.
     */ 
    function _output($content)
    {
        $data['content'] = &$content;
        echo($this->load->view('bootstrap_layout', $data, true));
    }

    /**
     * Determine if this is a post back so you can do that isPostBack thing
     * like they do in ASP and ASP.NET.  Strange that you don't really see
     * it elsewhere but there you go.
     */
    function isPostBack()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}