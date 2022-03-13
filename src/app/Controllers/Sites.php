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
			$nameType = $this->request->getVar('name-type');
			$speciesGroup = $this->request->getVar('species-group');
			$axiophyteFilter = $this->request->getVar('axiophyte-filter');
			if (!isset($axiophyteFilter)) $axiophyteFilter="false";

			// If there is a site name specified, assume we want are doing a search for species at
			// that site. If not, go to species in county as normal.
			$siteName = $this->request->getVar('site-name');
			if (isset($siteName))
			{
				return redirect()->to("/site/{$siteName}/group/{$speciesGroup}/type/{$nameType}/axiophyte/{$axiophyteFilter}");
			}
			else
			{
				$siteSearchString = $this->request->getVar('search');
            	$siteSearchString = trim($siteSearchString);
            	if (empty($siteSearchString))
				{
					$siteSearchString = "A";
				}

            	return redirect()->to("sites/{$siteSearchString}");
			}
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

		$nameTypeCookie = get_cookie("nameType");
		if (isset($nameTypeCookie))
		{
			$this->data['nameType'] = $nameTypeCookie;
		}
		else
		{
			$this->data['nameType'] = "scientific";
		}

		$speciesGroupCookie = get_cookie("speciesGroup");
		if (isset($speciesGroupCookie))
		{
			$this->data['speciesGroup'] = $speciesGroupCookie;
		}
		else
		{
			$this->data['speciesGroup'] = "both";
		}
		$axiophyteFilterCookie = get_cookie("axiophyteFilter");
		if (isset($axiophyteFilterCookie))
		{
			$this->data['axiophyteFilter'] = $axiophyteFilterCookie;
		}
		else
		{
			$this->data['axiophyteFilter'] = "";
		}


        echo view('sites_search', $this->data);
    }
}
