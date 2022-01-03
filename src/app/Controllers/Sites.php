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
            $siteSearchString = $this->request->getVar('search');
            $siteSearchString = trim($siteSearchString);
            if (empty($siteSearchString))
			{
				$siteSearchString = "A";
			}
            return redirect()->to("sites/{$siteSearchString}");
        };

		$this->data['siteSearchString'] = "";
        echo view('sites_search', $this->data);
    }

    /**
     * Display a list of sites in the county
     */
    public function listForCounty($siteSearchString)
    {

		$this->data['title'] 			= $this->data['title'] . ' - ' . $siteSearchString;
        $this->data['siteSearchString'] = $siteSearchString;
		$siteQueryResults 				= $this->nbn->getSiteListForCounty($siteSearchString, $this->page);
        $this->data['sites'] 			= $siteQueryResults->sites;
		$this->data['queryUrl'] 		= $siteQueryResults->queryUrl;
		$this->data['page'] 			= $this->page;
		$this->data['totalRecords'] 	= $siteQueryResults->totalRecords;
		$this->data['totalPages']       = $siteQueryResults->getTotalPages();

		set_cookie("siteNameSearch", $siteSearchString, "3600", "", "/", "", false, false, null);

        echo view('sites_search', $this->data);
    }
}
