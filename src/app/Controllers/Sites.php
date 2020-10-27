<?php namespace App\Controllers;

/**
 * Manage the sites views.
 */
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
            $this->data['sites'] = $this->nbnQuery->getSites($site_search_string);
        };
        echo view('sites_search', $this->data);
    }

}