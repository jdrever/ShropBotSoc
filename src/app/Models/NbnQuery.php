<?php namespace App\Models;

use App\Libraries\NbnRecords;

/**
 * A facade for the NBN API
 * 
 * Referenced at <https://api.nbnatlas.org/> the available search fields 
 * listed at <https://records-ws.nbnatlas.org/index/fields>.
 */
class NbnQuery implements NbnQueryInterface
{
  const NBN_GROUPS = 'https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=%s'.
    '&fq=data_resource_uid:dr782+AND+species_group:Plants+Bryophytes+AND+%s'.
    '&sort=taxon_name&fsort=index&pageSize=9';
  /**
   * Get an alphabetical list of taxa.
   * 
   * https://records-ws.nbnatlas.org/explore/group/Birds?fq=data_resource_uid:dr782+AND+taxon_name:B*&pageSize=12
   * https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?fq=data_resource_uid:dr782+AND+taxon_name:B*&pageSize=12
   * https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=&fq=data_resource_uid:dr782+AND+taxon_name:Bar*+AND+species_group:Plants+Bryophytes&pageSize=12
   * 
   * TODO: Implement search in common names and axiophytes
   * TODO: Only plants, only bryophytes or both
   */
  public function getSpeciesInDataset($taxon_search_string, $name_type)
  {
    $taxon_search_string = ucfirst($taxon_search_string); //because the API respects the case
    $nbn_records = new NbnRecords('explore/group/ALL_SPECIES');
    $nbn_records
      ->add('fq=taxon_name:'.$taxon_search_string.'*')
      ->add('fq=species_group:Plants+Bryophytes')
    ;
    $taxa_url = sprintf(self::NBN_GROUPS, NULL, "taxon_name:$taxon_search_string*");
    $taxa_json = file_get_contents($taxa_url);
    $get_taxa = json_decode($taxa_json);
    return $get_taxa;
  }


  const NBN_RECORDS = 'https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=%s'.
  '&sort=taxon_name&fsort=index&pageSize=9';
  /**
  * Get the records for a single taxon
  * 
  * Not sure what to make of the NBN API, I find it confusing but I'll use this URL.
  * https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=12
  * 
  * The taxon needs to be in double quotes so the complete string is searched for rather than a partial.
  * 
  * TODO: Needs caching
  * 
  * (data_resource_uid:dr78 AND pageSize:9 AND taxon_name:Acaulon%20muticum AND sort:taxon_name)
  */
  public function getRecordsForASpecies($taxon_name)
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

  const NBN_SITES = 'https://records-ws.nbnatlas.org/occurrences/search?fq=location_id:[Shrews%20TO%20*]&fq=data_resource_uid:dr782&facets=location_id&facet=on&pageSize=0';
  /**
   * Search for sites matching the string
   */
  public function getSites($site_search_string)
  {
    $sites_json = file_get_contents(self::NBN_SITES);
    $sites = json_decode($sites_json)->facetResults[0]->fieldResult;
    $sites = truncateArray(9, $sites);
    return $sites;
  }

  const NBN_SPECIES_FOR_A_SITE = "https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=&fq=data_resource_uid:dr782+AND+location_id:Shrewsbury+AND+species_group:Plants+Bryophytes&pageSize=9";
  /**
   * Get the site 
   */
  public function getSiteSpeciesList($site_name)
  {
    $species_json = file_get_contents(self::NBN_SPECIES_FOR_A_SITE);
    $site_species_list = json_decode($species_json);
    return $site_species_list;
  }

  const NBN_SINGLE_RECORD = 'https://records-ws.nbnatlas.org/occurrence/4276e1be-b7d2-46b0-a33d-6fa82e97636a';
  public function getRecord($uuid)
  {
    $record_json = file_get_contents(self::NBN_SINGLE_RECORD);
    $record = json_decode($record_json);
    return $record;
  }
}
