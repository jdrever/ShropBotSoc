<?php namespace App\Controllers;

/**
 * Manage the grid square views.
 */
class Squares extends BaseController
{
    private $data = array('title' => 'Squares');

    /**
     * Landing page showing the tetrads search form.
     */
    public function index()
    {
		if ($this->isPostBack())
        {
			$nameType = $this->request->getVar('name-type');
			$speciesGroup = $this->request->getVar('species-group');
			$axiophyteFilter = $this->request->getVar('axiophyte-filter');
			if (!isset($axiophyteFilter)) $axiophyteFilter="false";

			// If there is a square specified then we are searching at a square
			// and want to change the group/type
			$square = $this->request->getVar('square');
			if (isset($square))
			{
				return redirect()->to("/square/{$square}/group/{$speciesGroup}/type/{$nameType}/axiophyte/{$axiophyteFilter}");
			}
        };

		// Squares search needs to know about species group and name type
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

		// Get map position and zoom data from cookie
		$mapStateCookie = get_cookie("mapState");
		if (isset($mapStateCookie))
		{
			$this->data['mapState'] = $mapStateCookie;
		}
		else
		{
			$this->data['mapState'] = "52.6354,-2.71975,9";
		}

        echo view('squares_search', $this->data);
    }
}
