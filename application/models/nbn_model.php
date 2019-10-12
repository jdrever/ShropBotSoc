<?php

class Nbn_model extends CI_Model
{
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
            $groups_url = "https://records-ws.nbnatlas.org/explore/groups?q=data_resource_uid:dr782";
            $groups_json = file_get_contents($groups_url);
            $get_groups = json_decode($groups_json);
            $this->cache->save($cache_name, $get_groups, CACHE_TIME);
        }
        return $get_groups;
    }

    /**
     * Just getting the A records as a demonstration
     * 
     * https://records-ws.nbnatlas.org/occurrence/facets?facets=taxon_name&q=data_resource_uid:dr782+AND+taxon_name:A*
     */
    public function getTaxa()
    {
        $cache_name = 'get_taxa';
        if ( ! $get_taxa = $this->cache->get($cache_name))
        {
            $taxa_url = "https://records-ws.nbnatlas.org/occurrence/facets?facets=taxon_name&q=data_resource_uid:dr782+AND+taxon_name:A*&sort=taxon_name&fsort=index";
            $taxa_json = file_get_contents($taxa_url);
            $get_taxa = json_decode($taxa_json);
            $this->cache->save($cache_name, $get_taxa, CACHE_TIME);
        }
        return $get_taxa[0]->fieldResult;
    }
}
