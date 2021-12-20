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
            if (empty($site_search_string))
			{
				$site_search_string = "A";
			}
            return redirect()->to("sites/{$site_search_string}");
        };
		$this->data['siteSearchString'] = "";
        echo view('sites_search', $this->data);
    }

    /**
     * Display a list of sites in the county
     */
    public function listForCounty($siteSearchString)
    {
        $this->data['siteSearchString'] = $siteSearchString;
		$siteQueryResults = $this->nbn->getSiteListForCounty($siteSearchString, $this->page);
        $this->data['sites'] = $siteQueryResults->sites;
		$this->data['queryUrl'] = $siteQueryResults->queryUrl;
		$this->data['page'] = $this->page;
		$this->data['totalRecords'] = $siteQueryResults->totalRecords;
		$this->data['totalPages']       = $siteQueryResults->getTotalPages();

		set_cookie("siteNameSearch",$siteSearchString,"3600", "", "/", "", false, false, null);

        echo view('sites_search', $this->data);
    }
}
