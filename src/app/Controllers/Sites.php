<?php namespace App\Controllers;

class Sites extends BaseController
{
    private $data = array('title' => 'Sites');

    /**
     * Landing page showing the sites search form.
     */
    public function index()
    {
        if ($this->request->getVar('search') == null) 
        {
            // Don't show anything
        }
        else // 
        {
            $this->data['title'] = $this->data['title']." - results";
            $site_search_string = $this->request->getVar('search');
            $this->data['search'] = $site_search_string;
            $this->data['sites'] = $this->nbnModel->getSites($site_search_string);
        }
        echo view('sites_search', $this->data);
    }
}