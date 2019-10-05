<?php

class Records_model extends CI_Model
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

}
