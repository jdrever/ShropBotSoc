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

			// If there is a square specified then we are searching at a square
			// and want to change the group/type
			$square = $this->request->getVar('square');
			if (isset($square))
			{
				return redirect()->to("/square/{$square}/group/{$speciesGroup}/type/{$nameType}");
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

        echo view('squares_search', $this->data);
    }
}
