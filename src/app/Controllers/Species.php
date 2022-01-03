<?php namespace App\Controllers;

/**
 * Manage the species or taxa views.
 */
class Species extends BaseController
{
	private $data = ['title' => 'Species'];

	/**
	 * If it is a post back return a search of the species listed in the dataset
	 */
	public function index()
	{
		if ($this->isPostBack())
		{
			$nameType = $this->request->getVar('name-type');
			$speciesGroup = $this->request->getVar('species-group');

			// If there is a site name specified, assume we want to do a search for species at
			// that site. If not, go to species in county as normal.
			$siteName = $this->request->getVar('site-name');
			if (isset($siteName))
			{
				return redirect()->to("/site/{$siteName}/group/{$speciesGroup}/type/{$nameType}");
			}
			else
			{
				$nameSearchString  = $this->request->getVar('search');
				$nameSearchString  = trim($nameSearchString);
				// If the search field is empty, go to the begining of the alphabet
				if (empty($nameSearchString))
				{
					$nameSearchString = "A";
				}

				return redirect()->to("/species/{$nameSearchString}/group/{$speciesGroup}/type/{$nameType}");
			}
		};

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

		$this->data['nameSearchString'] = "";

		echo view('species_search', $this->data);
	}

	/**
	 * Return the species list for the county
	 */
	public function listForCounty($nameSearchString, $speciesGroup, $nameType)
	{
		$this->data['title']            = $this->data['title'] . " - " . $nameSearchString;
		$speciesQueryResult             = $this->nbn->getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup, $this->page);
		$this->data['records']          = $speciesQueryResult->records;
		$this->data['sites']            = $speciesQueryResult->sites;
		$this->data['downloadLink']     = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']         = $speciesQueryResult->queryUrl;
		$this->data['message']          = $speciesQueryResult->message;
		$this->data['nameSearchString'] = $nameSearchString;
		$this->data['nameType']         = $nameType;
		$this->data['speciesGroup']     = $speciesGroup;
		$this->data['page']             = $this->page;
		$this->data['totalRecords']     = $speciesQueryResult->totalRecords;
		$this->data['totalPages']       = $speciesQueryResult->getTotalPages();

		set_cookie("speciesNameSearch",$nameSearchString,"3600", "", "/", "", false, false, null);
		set_cookie("nameType", $nameType, "3600", "", "/", "", false, false, null);
		set_cookie("speciesGroup", $speciesGroup, "3600", "", "/", "", false, false, null);

		echo view('species_search', $this->data);
    }

	/**
	 * Return the species list for a named site.
	 */
	public function listForSite($siteName, $speciesGroup, $nameType)
	{
		$this->data['siteName']     = urldecode($siteName);
		$this->data['title']        = $this->data['title'] . " - " . $this->data['siteName'];
		$speciesQueryResult         = $this->nbn->getSpeciesListForSite($siteName, $nameType, $speciesGroup, $this->page);
		$this->data['speciesList']  = $speciesQueryResult->records;
		$this->data['siteLocation']	= $speciesQueryResult->siteLocation;
		$this->data['downloadLink'] = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']     = $speciesQueryResult->queryUrl;
		$this->data['message']      = $speciesQueryResult->message;
		$this->data['nameType']     = $nameType;
		$this->data['speciesGroup'] = $speciesGroup;
		$this->data['page']         = $this->page;
		$this->data['totalRecords'] = $speciesQueryResult->totalRecords;
		$this->data['totalPages']   = $speciesQueryResult->getTotalPages();

		$this->data['siteNameSearch'] = get_cookie("siteNameSearch");

		set_cookie("nameType", $nameType, "3600", "", "/", "", false, false, null);
		set_cookie("speciesGroup", $speciesGroup, "3600", "", "/", "", false, false, null);

		echo view('site_species_list', $this->data);
	}

	/**
	 * Return the species list for a square.
	 */
	public function listforSquare($gridSquare, $speciesGroup, $nameType)
	{
		$speciesQueryResult             = $this->nbn->getSpeciesListForSquare($gridSquare, $speciesGroup, $this->page);
		if (strlen($gridSquare)>6)
		{
			$gridSquare=substr($gridSquare,0,4) . substr($gridSquare,5,2);
		}

		$this->data['speciesList']      = $speciesQueryResult->records;
		$this->data['sites']            = $speciesQueryResult->sites;
		$this->data['downloadLink']     = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']         = $speciesQueryResult->queryUrl;
		$this->data['message']          = $speciesQueryResult->message;
		$this->data['speciesGroup'] = $speciesGroup;
		$this->data['page'] = $this->page;
		echo view('square_species_list', $this->data);
	}
}
