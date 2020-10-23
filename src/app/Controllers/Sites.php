<?php namespace App\Controllers;

class Sites extends BaseController
{
    private $data = array('title' => 'Sites');

    /**
     * Search for the site name or part there of
     */
    public function index()
    {
        if ($this->isPostBack()) 
        {
            $this->data['title'] = $this->data['title']." - results";
            $site_search_string = $this->request->getVar('search');
            $this->data['search'] = $site_search_string;
            $this->data['sites'] = $this->nbnModel->getSites($site_search_string);
        };
        echo view('sites_search', $this->data);
    }

    /**
     * 
     */
    public function speciesInSite($siteName)
    {
        echo view('site_species', $this->data);
    }

}