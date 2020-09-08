<?php namespace App\Controllers;

class Sites extends BaseController
{
    private $data = array('title' => 'Sites');

    /**
     * Landing page showing the sites search form.
     */
    public function index()
    {
        if ($this->isPostBack()) // post back
        {
            $this->data['title'] = $this->data['title']." - results";
            $siteName = $this->input->post('site-name')
            $this->data['siteName'] = $siteName;
        }
        else // not a post back but the first viewing
        {
        }
        echo view('sites_search', $this->data);
    }
}