<?php namespace App\Models;

/**
 * A facade for the NBN API
 * 
 * Referenced at <>
 * available search fields at <https://records-ws.nbnatlas.org/index/fields>.
 */
class NbnModel
{
    // Select from the groups `Plants` and `Bryophytes`
    const NBN_GROUPS = 'https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=%s'.
                        '&fq=data_resource_uid:dr782+AND+species_group:Plants+Bryophytes+AND+%s'.
                        '&sort=taxon_name&fsort=index&pageSize=9';
    const NBN_RECORDS = 'https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=%s'.
                        '&sort=taxon_name&fsort=index&pageSize=9';
    const NBN_SITES = 'https://records-ws.nbnatlas.org/occurrences/search?fq=location_id:[Shrews%20TO%20*]&fq=data_resource_uid:dr782&facets=location_id&facet=on&pageSize=0';


    /**
     * 
     */
    private function IsNullOrEmptyString($str){
        return (!isset($str) || trim($str) === '');
    }


    /**
     * Get an alphabetical list of taxa.
     * 
     * https://records-ws.nbnatlas.org/explore/group/Birds?fq=data_resource_uid:dr782+AND+taxon_name:B*&pageSize=12
     * https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?fq=data_resource_uid:dr782+AND+taxon_name:B*&pageSize=12
     * https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=&fq=data_resource_uid:dr782+AND+taxon_name:Bar*+AND+species_group:Plants+Bryophytes&pageSize=12
     * 
     * TODO: Implement search in common names and axiophytes
     */
    public function getTaxa($taxon_search_string, $name_type)
    {
        // So the cache files look neater
        if ($this->IsNullOrEmptyString($taxon_search_string)) $taxon_search_string = "A"; 
        $taxon_search_string = ucfirst($taxon_search_string); //because the API respects the case
        $cache_name = "get-taxa-$name_type-$taxon_search_string";
        if ( ! $get_taxa = cache($cache_name))
        {
            $taxa_url = sprintf(self::NBN_GROUPS, NULL, "taxon_name:$taxon_search_string*");
            $taxa_json = file_get_contents($taxa_url);
            $get_taxa = json_decode($taxa_json);
            cache()->save($cache_name, $get_taxa, CACHE_LIFE);
        }
        return $get_taxa;
    }

    /**
     * Get the records for a single taxon
     * 
     * Not sure what to make of the NBN API, I find it confusing but I'll use this URL.
     * https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=12
     * 
     * The taxon needs to be in double quotes so the complete string is searched for rather than a partial.
     * 
     * TODO: Needs caching
     */
    public function getRecords($taxon_name)
    {
        $taxon_name = rawurlencode($taxon_name); // mainly to replace the spaces with %20
        $records_url = sprintf(self::NBN_RECORDS, "taxon_name:$taxon_name");
        $records_json = file_get_contents($records_url);
        $get_records = json_decode($records_json)->occurrences;
        usort($get_records, function ($a, $b) {
            return $b->year <=> $a->year;
        });
        return $get_records;
    }


    /**
     * 
     */
    public function getSites($site_search_string)
    {
        $sites_json = file_get_contents(self::NBN_SITES);
        $get_sites = json_decode($sites_json)->facetResults;
        return $get_sites;
    }


}
