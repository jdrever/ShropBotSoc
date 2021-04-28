<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Provides caching layer on top of the NbnQuery methods
 *
 * TODO: #28 refactoring to implement DRY with repeated caching code
 */
class NbnQueryCached extends Model implements NbnQueryInterface
{
	/**
	 * Determines whether caching is active
	 * Set to false on development environments
	 *
	 * @var    bool
	 * @access private
	 */
	private const CACHE_ACTIVE = true;

	/**
	 * Constructor, initialises NbnQuery
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->nbnQuery = model('App\Models\NbnQuery', false);
	}

	/**
	 * Cache the search for species within the county
	 *
	 * @param string  $nameSearchString The species name
	 * @param string  $nameType         Scientific or common
	 * @param string  $speciesGroup     Plants, bryophytes or both
	 * @param int     $page             The page of results to return
	 *
	 * @return NbnQueryResult
	 */
	public function getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup, $page)
	{
		//because the API respects the case
		$nameSearchString = ucfirst($nameSearchString);
		$cacheName        = "get-species-list-for-county-$nameType-$speciesGroup-$nameSearchString-$page";
		if (! self::CACHE_ACTIVE || ! $speciesList = cache($cacheName))
		{
			$speciesList = $this->nbnQuery->getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup, $page);
			if (self::CACHE_ACTIVE && $speciesList->status === 'OK')
			{
				cache()->save($cacheName, $speciesList, CACHE_LIFE);
			}
		}
		return $speciesList;
	}

	/**
	 * Cache the single species search within the county
	 *
	 * @param string $speciesName The name of the species
	 * @param string $page        The page of results to return
	 *
	 * @return NbnQueryResult
	 */
	public function getSingleSpeciesRecordsForCounty($speciesName, $page)
	{
		$cacheName = "get-single-species-for-county-$speciesName-$page";
		if (! self::CACHE_ACTIVE || ! $speciesRecords = cache($cacheName))
		{
			$speciesRecords = $this->nbnQuery->getSingleSpeciesRecordsForCounty($speciesName, $page);
			if (self::CACHE_ACTIVE && $speciesRecords->status === 'OK')
			{
				cache()->save($cacheName, $speciesRecords, CACHE_LIFE);
			}
		}
		return $speciesRecords;
	}

	/**
	 * Cache the single occurence record
	 *
	 * @param string $uuid the unique id for the occurence
	 *
	 * @return NbnQueryResult
	 */
	public function getSingleOccurenceRecord($uuid)
	{
		$cacheName = "get-single-occurence-record-$uuid";
		if (! self::CACHE_ACTIVE || ! $occurenceRecord = cache($cacheName))
		{
			$occurenceRecord = $this->nbnQuery->getSingleOccurenceRecord($uuid);
			if (self::CACHE_ACTIVE  && $occurenceRecord->status === 'OK')
			{
				cache()->save($cacheName, $occurenceRecord, CACHE_LIFE);
			}
		}
		return $occurenceRecord;
	}

	/**
	 * Cache the site list for the county
	 *
	 * @param string $siteSearchString The name of the site
	 *
	 * @return NbnQueryResult
	 */
	public function getSiteListForCounty($siteSearchString)
	{
		$nameSearchString = ucfirst($siteSearchString);
		$cacheName        = "get-site-list-for-county-$nameSearchString";
		if (! self::CACHE_ACTIVE || ! $siteList = cache($cacheName))
		{
			$siteList = $this->nbnQuery->getSiteListForCounty($nameSearchString);
			if (self::CACHE_ACTIVE && $siteList->status === 'OK' )
			{
				cache()->save($cacheName, $siteList, CACHE_LIFE);
			}
		}
		return $siteList;
	}

	/**
	 * Cache for site speciest list
	 *
	 * @param string $siteName     The name of the site
	 * @param string $speciesGroup Plants, bryophytes or both
	 *
	 * @return NbnQueryResult
	 */
	public function getSpeciesListForSite($siteName, $speciesGroup)
	{
		$cacheName = "get-species-list-for-site-$speciesGroup";
		if (! self::CACHE_ACTIVE || ! $speciesList = cache($cacheName))
		{
			$speciesList = $this->nbnQuery->getSpeciesListForSite($siteName, $speciesGroup);
			if (self::CACHE_ACTIVE && $speciesList->status === 'OK')
			{
				cache()->save($cacheName, $speciesList, CACHE_LIFE);
			}
		}
		return $speciesList;
	}

	/**
	 * Cache the single species record for a site
	 *
	 * @param string $siteName    The site name
	 * @param string $speciesName The species name
	 *
	 * @return NbnQueryResult
	 */
	public function getSingleSpeciesRecordsForSite($siteName, $speciesName)
	{
		$cacheName = "get-species-records-for-site-$siteName-$speciesName";
		if (! self::CACHE_ACTIVE || ! $speciesRecords = cache($cacheName))
		{
			$speciesRecords = $this->nbnQuery->getSingleSpeciesRecordsForSite($siteName, $speciesName);
			if (self::CACHE_ACTIVE && $speciesRecords->status === 'OK')
			{
				cache()->save($cacheName, $speciesRecords, CACHE_LIFE);
			}
		}
		return $speciesRecords;
	}

	/**
	 * Cache species list for square
	 *
	 * @param string $gridSquare   The grid square
	 * @param string $speciesGroup Plants, bryophytes or both
	 *
	 * @return NbnQueryResult
	 *
	 * TODO: implement speciesGroup filtering
	 */
	public function getSpeciesListForSquare($gridSquare, $speciesGroup, $page)
	{
		$cacheName = "get-species-list-for-square-$gridSquare-$speciesGroup";
		if (! self::CACHE_ACTIVE || ! $speciesList = cache($cacheName))
		{
			$speciesList = $this->nbnQuery->getSpeciesListForSquare($gridSquare, $speciesGroup,$page);
			if (self::CACHE_ACTIVE && $speciesList->status === 'OK')
			{
				cache()->save($cacheName, $speciesList, CACHE_LIFE);
			}
		}
		return $speciesList;
	}

	/**
	 * Cache for single species record in square
	 *
	 * @param string $gridSquare  The grid square
	 * @param string $speciesName The species name
	 *
	 * @return NbnQueryResult
	 */
	public function getSingleSpeciesRecordsForSquare($gridSquare, $speciesName)
	{
		$cacheName = "get-species-records-for-square-$gridSquare-$speciesName";
		if (! self::CACHE_ACTIVE || ! $speciesRecords = cache($cacheName))
		{
			$speciesRecords = $this->nbnQuery->getSingleSpeciesRecordsForSquare($gridSquare, $speciesName);
			if (self::CACHE_ACTIVE  && $speciesRecords->status === 'OK')
			{
				cache()->save($cacheName, $speciesRecords, CACHE_LIFE);
			}
		}
		return $speciesRecords;
	}
}
