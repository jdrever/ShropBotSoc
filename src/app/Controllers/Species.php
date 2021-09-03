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
			$this->data['title'] = $this->data['title'] . " - results";
			$name_search_string  = $this->request->getVar('search');
			$name_search_string  = trim($name_search_string);

			// If the search field is empty, go to the begining of the alphabet
			if (empty($name_search_string))
			{
				$name_search_string = "A";
			}
			$nameType      = $this->request->getVar('name-type');
			$species_group = $this->request->getVar('species-group');
			return redirect()->to("/species/{$name_search_string}/group/{$species_group}/type/{$nameType}");
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
	public function listForCounty($name_search_string, $species_group, $name_type)
	{
		$this->data['title']            = $this->data['title'] . " - " . $name_search_string;
		$speciesQueryResult             = $this->nbn->getSpeciesListForCounty($name_search_string, $name_type, $species_group, $this->page);
		$this->data['records']      = $speciesQueryResult->records;
		$this->data['sites']            = $speciesQueryResult->sites;
		$this->data['downloadLink']     = $speciesQueryResult->downloadLink;
		$this->data['queryUrl']         = $speciesQueryResult->queryUrl;
		$this->data['message']          = $speciesQueryResult->message;
		$this->data['nameSearchString'] = $name_search_string;
		$this->data['nameType']     = $name_type;
		$this->data['speciesGroup'] = $species_group;
		$this->data['page'] = $this->page;
		$this->data['totalRecords'] = $speciesQueryResult->totalRecords;
		$this->data['totalPages']       = $speciesQueryResult->getTotalPages();

		set_cookie("speciesNameSearch",$name_search_string,"3600", "", "/", "", false, false, null);
		set_cookie("nameType", $name_type);//, "3600", "localhost", "/", "", false, false, null);
		set_cookie("speciesGroup", $species_group);//, "3600", "localhost", "/", "", false, false, null);

		echo view('species_search', $this->data);
    }

	/**
	 * Return the species list for a named site.
	 */
	public function listForSite($site_name, $species_group, $name_type)
	{
		$this->data['title']       = urldecode($site_name);
		$species_group             = $this->request->getVar('species-group');
		$this->data['speciesList'] = $this->nbn->getSpeciesListForSite($site_name, $species_group);
		echo view('site_species_list', $this->data);
	}

	/**
	 * Return the species list for a square.
	 */
	public function listforSquare($gridSquare, $speciesGroup, $nameType)
	{
		$speciesQueryResult             = $this->nbn->getSpeciesListForSquare($gridSquare, $speciesGroup, $this->page);
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
