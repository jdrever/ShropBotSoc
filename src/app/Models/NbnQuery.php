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
  /**
   * Get an alphabetical list of species.
   * 
   * e.g. https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=data_resource_uid:dr782&fq=taxon_name:B* AND species_group:Plants Bryophytes&pageSize=9&sort=
   * 
   * TODO: Search in common names
   * TODO: Search on axiophytes
   * TODO: Only plants, only bryophytes or both
   */
  public function getSpeciesListForCounty($name_search_string, $name_type, $species_group)
  {
    $name_search_string = ucfirst($name_search_string); //because the API respects the case
    $nbn_records = new NbnRecords('explore/group/ALL_SPECIES');
    $nbn_records
      ->add('taxon_name:'.$name_search_string.'*')
      ->add('species_group:Plants+Bryophytes')
    ;
    $query_url = $nbn_records->getPagingQueryString();
    $species_list_json = file_get_contents($query_url);
    $species_list = json_decode($species_list_json);
    return $species_list;
  }

  /**
    * Get the records for a single species
    * 
    * e.g. https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=9
    * 
    * The taxon needs to be in double quotes so the complete string is searched for rather than a partial.
    */
  public function getSingleSpeciesRecordsForCounty($species_name)
  {
    $species_name = rawurlencode($species_name); // mainly to replace the spaces with %20
    $nbn_records = new NbnRecords('occurrences/search');
    $nbn_records->sort = "taxon_name";
    $nbn_records->fsort = "index";
    $nbn_records
      ->add('taxon_name:'.$species_name);
    ;
    $query_url = $nbn_records->getPagingQueryString();
    $records_json = file_get_contents($query_url);
    $record_list = json_decode($records_json)->occurrences;
    usort($record_list, function ($a, $b) {
      return $b->year <=> $a->year;
    });
    $records['download_link'] = $nbn_records->getDownloadQueryString();
    $records['records_list'] = $record_list;
    return $records;
  }

  /**
   * Get a single record
   * 
   * e.g. https://records-ws.nbnatlas.org/occurrence/4276e1be-b7d2-46b0-a33d-6fa82e97636a
   */
  public function getSingleOccurenceRecord($uuid)
  {
    $nbn_records = new NbnRecords('occurrence/');
    $record_json = file_get_contents($nbn_records->url().$uuid);
    $record = json_decode($record_json);
    return $record;
  }

  /**
   * Search for sites matching the string
   * 
   * e.g. 'https://records-ws.nbnatlas.org/occurrences/search?fq=location_id:[Shrews%20TO%20*]&fq=data_resource_uid:dr782&facets=location_id&facet=on&pageSize=0';
   */
  public function getSiteListForCounty($site_search_string)
  {
    $nbn_records = new NbnRecords('occurrences/search');
    $nbn_records->facets = "location_id";
    $nbn_records->pageSize = 0;
    $nbn_records
      ->add('location_id:['.$site_search_string.'%20TO%20*]');
    ;
    $query_url = $nbn_records->getPagingQueryString();
    $sites_json = file_get_contents($query_url);
    $sites_list = json_decode($sites_json)->facetResults[0]->fieldResult;
    $sites_list = truncateArray(9, $sites_list);
    return $sites_list;
  }

  /**
   * Get species list for a site.
   * 
   * e.g. 'https://records-ws.nbnatlas.org/explore/group/ALL_SPECIES?q=&fq=data_resource_uid:dr782+AND+location_id:Shrewsbury+AND+species_group:Plants+Bryophytes&pageSize=9'
   */
  public function getSpeciesListForSite($site_name, $species_group)
  {
    $nbn_records = new NbnRecords('explore/group/ALL_SPECIES');
    $nbn_records
      ->add('location_id:'.urlencode($site_name))
      ->add('species_group:Plants+Bryophytes')
    ;
    $query_url = $nbn_records->getPagingQueryString();
    $species_json = file_get_contents($query_url);
    $site_species_list = json_decode($species_json);
    return $site_species_list;
  }

  public function getSingleSpeciesRecordsForSite($site_name, $species_name)
  {
    return NULL;
  }

  public function getSpeciesListForSquare($grid_square, $species_group)
  {
    return NULL;
  }

  public function getSingleSpeciesRecordsForSquare($grid_square, $species_name)
  {
    return NULL;
  }

}
