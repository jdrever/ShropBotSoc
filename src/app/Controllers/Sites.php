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
            $site_search_string = trim($site_search_string);
            if (trim($site_search_string) == NULL) $site_search_string = "A";
            return redirect()->to("sites/{$site_search_string}");
        };
        echo view('sites_search', $this->data);
    }

    /**
     * Display a list of sites in the county
     */
    public function listForCounty($site_search_string)
    {
        $this->data['search'] = $site_search_string;
        $this->data['sites'] = $this->nbn->getSiteListForCounty($site_search_string);
        echo view('sites_search', $this->data);
    }
}