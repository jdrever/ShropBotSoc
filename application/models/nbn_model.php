<?php

class Nbn_model extends CI_Model
{
    private const NBN_URL = 'https://records-ws.nbnatlas.org/';
    private const FACET_PARAMETERS = '&sort=taxon_name&fsort=index&pageSize=12';

    public function __construct()
    {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }

   /**
     * Get the list of groups for SEDN from the NBN and cache them.
     */
    public function getGroups()
    {
        $cache_name = 'get_groups';
        if ( ! $get_groups = $this->cache->get($cache_name))
        {
            $groups_url = self::NBN_URL."explore/groups?q=data_resource_uid:dr782";
            $groups_json = file_get_contents($groups_url);
            $get_groups = json_decode($groups_json);
            $this->cache->save($cache_name, $get_groups, CACHE_TIME);
        }
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
        $taxon_search_string = ucfirst($taxon_search_string); //because the API respects the case
        $cache_name = "get-taxa-$group_name-$taxon_search_string";
        if ( ! $get_taxa = $this->cache->get($cache_name))
        {
            $taxa_url = self::NBN_URL."explore/group/$group_name?fq=data_resource_uid:dr782+AND+taxon_name:$taxon_search_string*".self::FACET_PARAMETERS;
            $taxa_json = file_get_contents($taxa_url);
            $get_taxa = json_decode($taxa_json);
            $this->cache->save($cache_name, $get_taxa, CACHE_TIME);
        }
        return $get_taxa;
    }

    /**
     * Get the records for a single taxon
     * 
     * Not sure what to make of the NBN API, I find it confusing but I'll use this URL.
     * https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=12
     * https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba
     * 
     * TODO: This doesn't seem to work correctly, the `json_decode` appears not to decode
     * the entirety of the json returned, so the things like the `collectors` are missing.  
     * I am not sure if it is matter of depth or some fixed limit in the PHP `json_decode`. 
     */
    public function getRecords($taxon_name)
    {
        $cache_name = "get-records-$taxon_name";
        if ( ! $get_records = $this->cache->get($cache_name))
        {
            $records_url = self::NBN_URL."occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:$taxon_name".self::FACET_PARAMETERS;
            $records_json = file_get_contents($records_url);
            $get_records = json_decode($records_json);
            $this->cache->save($cache_name, $get_records, CACHE_TIME);
        }
        return $get_records;
    }
}
