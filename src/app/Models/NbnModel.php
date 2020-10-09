<?php namespace App\Models;

/**
 * A fascade for the NBN API
 */
class NbnModel
{
    private const NBN_URL = 'https://records-ws.nbnatlas.org/';
    private const FACET_PARAMETERS = '&sort=taxon_name&fsort=index&pageSize=12';

    // public function __construct()
    // {
    //     $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    // }

    private function IsNullOrEmptyString($str){
        return (!isset($str) || trim($str) === '');
    }

   /**
     * Get the list of groups for SEDN from the NBN and cache them.
     */
    public function getGroups()
    {
        $groups_url = self::NBN_URL."explore/groups?q=data_resource_uid:dr782";
        $groups_json = file_get_contents($groups_url);
        $get_groups = json_decode($groups_json);
        return $get_groups;
    }

    /**
     * Just getting the records as a demonstration, not sure whether to use
     * the `occurrence` or the `explore` API.  I will use `explore` for the 
     * since it has a means of separating the groups.
     * 
     * https://records-ws.nbnatlas.org/occurrence/facets?facets=taxon_name&q=data_resource_uid:dr782+AND+taxon_name:A*
     * 
     * https://records-ws.nbnatlas.org/explore/group/Birds?fq=data_resource_uid:dr782+AND+taxon_name:B*&pageSize=12
     * https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?fq=data_resource_uid:dr782+AND+taxon_name:B*&pageSize=12
     */
    public function getTaxa($taxon_search_string, $group_name)
    {
        // So the cache files look neater
        if ($this->IsNullOrEmptyString($taxon_search_string)) $taxon_search_string = "A"; 
        $taxon_search_string = ucfirst($taxon_search_string); //because the API respects the case
        $taxa_url = self::NBN_URL."explore/group/$group_name?fq=data_resource_uid:dr782+AND+taxon_name:$taxon_search_string*".self::FACET_PARAMETERS;
        $taxa_json = file_get_contents($taxa_url);
        $get_taxa = json_decode($taxa_json);
        return $get_taxa;
    }

    /**
     * Get the records for a single taxon
     * 
     * Not sure what to make of the NBN API, I find it confusing but I'll use this URL.
     * https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=12
     * 
     * The taxon needs to be in double quotes so the complete string is searched for rather than a partial.
     */
    public function getRecords($taxon_name)
    {
        // Encoding needs to be different on Azure otherwise the API returns nothing...not sure why.
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $taxon_name = rawurlencode($taxon_name);
        $cache_name = urldecode($taxon_name);
        $cache_name = str_replace(' ', '-', $cache_name);
        $cache_name = strtolower($cache_name);
        $cache_name = "get-records-$cache_name";
        if ( ! $get_records = $this->cache->get($cache_name))
        {
            $records_url = self::NBN_URL."occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:\"$taxon_name\"".self::FACET_PARAMETERS;
            $records_json = file_get_contents($records_url);
            $get_records = json_decode($records_json)->occurrences;
            usort($get_records, function ($a, $b) {
                return $b->year <=> $a->year;
            });
            $this->cache->save($cache_name, $get_records, CACHE_TIME);
        }
        return $get_records;
    }


    /**
     * 
     */
    public function getSites($site_search_string)
    {
        return "shit";
    }


}
