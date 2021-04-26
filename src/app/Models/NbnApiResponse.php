<?php
/**
 *
 */
namespace App\Models;

/**
 * The response from the NBN API, including JSON response, status
 * and error message if one is required
 */
class NbnApiResponse
{
	/**
	 * The json response from the NBN API
	 *
	 * @var string
	 */
	public $jsonResponse;
	/**
	 * The status of the response from the NBN API
	 * Either OK or ERROR
	 *
	 * @var string
	 */
	public $status;
	/**
	 * The error message (if one is raised) from calling
	 * the NBN API
	 *
	 * @var string
	 */
	public $message;
}
?>
