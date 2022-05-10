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
			$axiophyteFilter = $this->request->getVar('axiophyte-filter');
			if (!isset($axiophyteFilter)) $axiophyteFilter="false";
			$speciesGroup = $this->request->getVar('species-group');

			$nameSearchString  = $this->request->getVar('search');
			$nameSearchString  = trim($nameSearchString);
			// If the search field is empty, go to the begining of the alphabet
			if (empty($nameSearchString))
			{
				$nameSearchString = "A";
			}

			return redirect()->to("/species/{$nameSearchString}/group/{$speciesGroup}/type/{$nameType}/axiophyte/$axiophyteFilter");
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


		$speciesNameSearch= get_cookie("speciesNameSearch");
		if (isset($speciesGroupCookie))
		{
			$this->data['nameSearchString'] = $speciesNameSearch;
		}
		else
		{
			$this->data['nameSearchString'] = "";
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

		echo view('species_search', $this->data);
	}

	/**
	 * Return the species list for the county
	 */
	public function listForCounty($nameSearchString, $speciesGroup, $nameType, $axiophyteFilter)
	{
		$this->data['title']            = $this->data['title'] . " - " . $nameSearchString;
		$speciesQueryResult             = $this->nbn->getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup, $axiophyteFilter, $this->page);
		$this->data['records']          = $speciesQueryResult->records;
		$this->data['sites']            = $speciesQueryResult->sites;
		$this->data['downloadLink']     = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']         = $speciesQueryResult->queryUrl;
		$this->data['message']          = $speciesQueryResult->message;
		$this->data['nameSearchString'] = $nameSearchString;
		$this->data['nameType']         = $nameType;
		$this->data['axiophyteFilter']  = $axiophyteFilter;
		$this->data['speciesGroup']     = $speciesGroup;
		$this->data['page']             = $this->page;
		$this->data['totalRecords']     = $speciesQueryResult->totalRecords;
		$this->data['totalPages']       = $speciesQueryResult->getTotalPages();

		set_cookie("speciesNameSearch",$nameSearchString,"3600", "", "/", "", false, false, null);
		set_cookie("nameType", $nameType, "3600", "", "/", "", false, false, null);
		set_cookie("axiophyteFilter", $axiophyteFilter, "3600", "", "/", "", false, false, null);
		set_cookie("speciesGroup", $speciesGroup, "3600", "", "/", "", false, false, null);

		echo view('species_search', $this->data);
    }

	/**
	 * Return the species list for a named site.
	 */
	public function listForSite($siteName, $speciesGroup, $nameType, $axiophyteFilter)
	{

		$this->data['siteName']     = urldecode($siteName);
		$this->data['title']        = $this->data['title'] . " - " . $this->data['siteName'];
		$speciesQueryResult         = $this->nbn->getSpeciesListForSite($siteName, $nameType, $speciesGroup, $axiophyteFilter, $this->page);
		$this->data['speciesList']  = $speciesQueryResult->records;
		$this->data['siteLocation']	= $speciesQueryResult->siteLocation;
		$this->data['downloadLink'] = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']     = $speciesQueryResult->queryUrl;
		$this->data['message']      = $speciesQueryResult->message;
		$this->data['nameType']     = $nameType;
		$this->data['axiophyteFilter']  = $axiophyteFilter;
		$this->data['speciesGroup'] = $speciesGroup;
		$this->data['page']         = $this->page;
		$this->data['totalRecords'] = $speciesQueryResult->totalRecords;
		$this->data['totalPages']   = $speciesQueryResult->getTotalPages();

		$this->data['siteNameSearch'] = get_cookie("siteNameSearch");

		set_cookie("nameType", $nameType, "3600", "", "/", "", false, false, null);
		set_cookie("speciesGroup", $speciesGroup, "3600", "", "/", "", false, false, null);
		set_cookie("axiophyteFilter", $axiophyteFilter, "3600", "", "/", "", false, false, null);

		echo view('site_species_list', $this->data);
	}

	/**
	 * Return the species list for a square.
	 */
	public function listforSquare($gridSquare, $speciesGroup, $nameType, $axiophyteFilter)
	{
		$speciesQueryResult             = $this->nbn->getSpeciesListForSquare($gridSquare, $speciesGroup, $nameType, $axiophyteFilter, $this->page);

		$this->data['speciesList']      = $speciesQueryResult->records;
		$this->data['sites']            = $speciesQueryResult->sites;
		$this->data['downloadLink']     = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']         = $speciesQueryResult->queryUrl;
		$this->data['message']          = $speciesQueryResult->message;
		$this->data['status']          = $speciesQueryResult->status;
		$this->data['totalRecords'] = $speciesQueryResult->totalRecords;
		$this->data['totalPages']   = $speciesQueryResult->getTotalPages();

		$this->data['page'] = $this->page;
		$this->data['speciesGroup'] = $speciesGroup;
		$this->data['gridSquare'] = $gridSquare;
		$this->data['nameType']     = $nameType;
		$this->data['axiophyteFilter']  = $axiophyteFilter;

		set_cookie("nameType", $nameType, "3600", "", "/", "", false, false, null);
		set_cookie("speciesGroup", $speciesGroup, "3600", "", "/", "", false, false, null);
		set_cookie("axiophyteFilter", $axiophyteFilter, "3600", "", "/", "", false, false, null);
		echo view('square_species_list', $this->data);
	}
}
