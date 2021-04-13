<?php namespace App\Models;

/**
 * Provides caching layer on top of the NbnQuery methods
 */
class NbnQueryCached implements NbnQueryInterface
{
	/**
	 * Determines whether caching is active
	 * Set to false on development environments
	 *
	 * @var    bool
	 * @access private
	 *
	 * @TODO: refactoring to implement DRY with repeated caching code
	 */
	private const CACHE_ACTIVE = true;

	/**
	 * Constructor, initiliases NbnQuery
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
	 * @param [string] $nameSearchString the species name
	 * @param [string] $nameType         scientific or common
	 * @param [string] $speciesGroup     plants, bryophytes or both
	 *
	 * @return nbnQueryResult
	 */
	public function getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup)
	{
		$nameSearchString = ucfirst($nameSearchString); //because the API respects the case
		$cacheName         = "get-species-list-for-county-$nameType-$speciesGroup-$nameSearchString";
		if (! self::CACHE_ACTIVE || ! $speciesList = cache($cacheName))
		{
			$speciesList = $this->nbnQuery->getSpeciesListForCounty($nameSearchString, $nameType, $speciesGroup);
			if (self::CACHE_ACTIVE)
			{
				cache()->save($cacheName, $speciesList, CACHE_LIFE);
			}
		}
		return $speciesList;
	}

	/**
	 * Cache the single species search within the county
	 *
	 * @param [string] $speciesName the name of the species
	 *
	 * @return nbnQueryResult
	 */
	public function getSingleSpeciesRecordsForCounty($speciesName)
	{
		$cacheName = "get-single-species-for-county-$speciesName";
		if (! self::CACHE_ACTIVE || ! $speciesRecords = cache($cacheName))
		{
			$speciesRecords = $this->nbnQuery->getSingleSpeciesRecordsForCounty($speciesName);
			if (self::CACHE_ACTIVE)
			{
				cache()->save($cacheName, $speciesRecords, CACHE_LIFE);
			}
		}
		return $speciesRecords;
	}

	/**
	 * Cache the single occurence record
	 *
	 * @param [string] $uuid the unique id for the occurence
	 *
	 * @return [nbnQueryResult]
	 */
	public function getSingleOccurenceRecord($uuid)
	{
		$cacheName = "get-single-occurence-record-$uuid";
		if (! self::CACHE_ACTIVE || ! $occurenceRecord = cache($cacheName))
		{
			$occurenceRecord = $this->nbnQuery->getSingleOccurenceRecord($uuid);
			if (self::CACHE_ACTIVE)
			{
				cache()->save($cacheName, $occurenceRecord, CACHE_LIFE);
			}
		}
		return $occurenceRecord;
	}

	/**
	 * Cache the site list for the county
	 *
	 * @param [string] $siteSearchString the name of the site
	 *
	 * @return [nbnQueryResult]
	 */
	public function getSiteListForCounty($siteSearchString)
	{
		$nameSearchString = ucfirst($siteSearchString);
		$cacheName         = "get-site-list-for-county-$nameSearchString";
		if (! self::CACHE_ACTIVE || ! $siteList = cache($cacheName))
		{
			$siteList = $this->nbnQuery->getSiteListForCounty($nameSearchString);
			if (self::CACHE_ACTIVE)
			{
				cache()->save($cacheName, $siteList, CACHE_LIFE);
			}
		}
		return $siteList;
	}

	/**
	 * Cache for site speciest list
	 *
	 * @param [string] $sitename     the name of the site
	 * @param [string] $speciesGroup plants, bryophytes or both
	 *
	 * @return nbnQueryResult
	 */
	public function getSpeciesListForSite($siteName, $speciesGroup)
	{
		$cacheName = "get-species-list-for-site-$speciesGroup";
		if (! self::CACHE_ACTIVE || ! $speciesList = cache($cacheName))
		{
			$speciesList = $this->nbnQuery->getSpeciesListForSite($siteName, $speciesGroup);
			if (self::CACHE_ACTIVE)
			{
				 cache()->save($cacheName, $speciesList, CACHE_LIFE);
			}
		}
		return $speciesList;
	}

	/**
	 * Cache the single species record for a site
	 *
	 * @param [string] $siteName    the site name
	 * @param [string] $speciesName the species name
	 *
	 * @return [nbnQueryResult]
	 */
	public function getSingleSpeciesRecordsForSite($siteName, $speciesName)
	{
		$cacheName = "get-species-records-for-site-$siteName-$speciesName";
		if (! self::CACHE_ACTIVE || ! $speciesList = cache($cacheName))
		{
			$speciesRecords = $this->nbnQuery->getSingleSpeciesRecordsForSite($siteName, $speciesName);
			if (self::CACHE_ACTIVE)
			{
				 cache()->save($cacheName, $speciesRecords, CACHE_LIFE);
			}
		}
		return $speciesRecords
	}

	/**
	 * Cache species list for square
	 *
	 * @param [string] $gridSquare  the grid square
	 * @param [string] $speciesGroup plants, bryophytes or both
	 *
	 * @return [nbnQueryResult]
	 *
	 * @TODO: implement speciesGroup filtering
	 */
	public function getSpeciesListForSquare($gridSquare, $speciesGroup)
	{
		return $this->nbnQuery->getSpeciesListForSquare($gridSquare);
	}

	/**
	 * TODO: caching
	 */
	public function getSingleSpeciesRecordsForSquare($grid_square, $speciesName)
	{
		return $this->nbnQuery->getSingleSpeciesRecordsForSquare($grid_square, $speciesName);
	}
}
