<?php

class Shrops_model extends CI_Model
{
    public function __construct()
    {
    }

    public function get_records_for_species($spid, $limit, $offset)
    {
        //Query the database and get the records for the species identified by
        //$spid as wel as a query string to be used later to generate a csv for download

        //Do the main query
        $this->db->limit($limit, $offset);
        $this->db->select('recid,siteName,siteid,recFullgrid,recTetrad,recRecorders,recYear');
        $this->db->join('sites', 'sites.siteid = records.recSite_id', 'INNER');
        $this->db->where('recSpecies_id', $spid);
        $query = $this->db->get('records');

        //Capture the last query as a string (to use in generating a csv later
        $csv_qry_str = $this->db->last_query();

        //Iterate through the results and copy to a new array $records,
        // converting the id value in each row to a link
        $records = array();
        foreach ($query->result_array() as $row) {
            $row['recid'] = anchor('shrops/detail/record/' . $row['recid'], 'Full Details', 'title="Record Details"');

            $row['siteName'] = anchor('shrops/showrecords/sites/' . $row['siteid'], $row['siteName'], 'title="Species for Site"');

            $records[] = $row;
            //Now remove the 'siteid'. We needed to select it to build the anchor on the site name,
            //but we don't want to display it in the Views table

            for ($i = 0; $i < count($records); ++$i) {
                unset($records[$i]['siteid']);
            }
        }
        //Add the main results and the query string for csv to an array
        $results = array();
        $results['main'] = $records;
        $results['csvqry'] = $csv_qry_str;

        return $results;
    }

    public function get_records_for_sites($siteid, $limit, $offset)
    {
        //Query the database and get the records for the sites identified by
        //$siteid
        $this->db->limit($limit, $offset);
        $this->db->select('recid,sppName,sppid,sppCommon,grpName,recFullgrid,recRecorders,recYear');
        $this->db->from('records');
        $this->db->join('species', 'species.sppid = records.recSpecies_id', 'INNER');
        $this->db->join('group', 'species.sppGroup = group.grpid', 'INNER');

        //$this->db->join('person', 'person.prsid = records.recColl_id','INNER');
        $this->db->where('recSite_id', $siteid);
        $this->db->order_by('sppGroup asc,sppName asc');
        $query = $this->db->get();

        //Capture the last query as a string (to use in generating a csv later
        $csv_qry_str = $this->db->last_query();
        //Iterate through the results and copy to a new array $records,
        // converting the id value in each row to a link
        $records = array();
        foreach ($query->result_array() as $row) {
            $row['recid'] = anchor('shrops/detail/record/' . $row['recid'], 'Full Details', 'title="Record Details"');
            $row['sppName'] = anchor('shrops/showrecords/species/' . $row['sppid'], $row['sppName'], 'title="Records for Species"');

            $records[] = $row;
            //Remove the species id - we just want the name displayed
            for ($i = 0; $i < count($records); ++$i) {
                unset($records[$i]['sppid']);
            }
        }

        $results = array();
        $results['main'] = $records;
        $results['csvqry'] = $csv_qry_str;
        return $results;
    }

    public function get_records_for_tetrads($tetrad, $limit, $offset)
    {
        //Query the database and get the records for the tetrads identified by
        //$tetrad - from text box of map click
        $this->db->limit($limit, $offset);
        $this->db->select('recid,sppName,sppid,sppCommon,grpName,recFullgrid,recYear');
        $this->db->from('records');
        $this->db->join('species', 'species.sppid = records.recSpecies_id', 'INNER');
        $this->db->join('group', 'group.grpid = species.sppGroup');
        //$this->db->join('person', 'person.prsid = records.recColl_id','INNER');
        $this->db->where('recTetrad', $tetrad);
        $this->db->order_by('sppName', 'asc');
        $query = $this->db->get();
        //Capture the last query as a string (to use in generating a csv later
        $csv_qry_str = $this->db->last_query();
        //Iterate through the results and copy to a new array $records,
        // converting the id value in each row to a link
        $records = array();
        foreach ($query->result_array() as $row) {
            $row['recid'] = anchor('shrops/detail/record/' . $row['recid'], 'Full Details', 'title="Record Details"');
            $row['sppName'] = anchor('shrops/showrecords/species/' . $row['sppid'], $row['sppName'], 'title="Records for Tetrads"');

            $records[] = $row;

            for ($i = 0; $i < count($records); ++$i) {
                unset($records[$i]['sppid']);
            }
        }
        $results = array();
        $results['main'] = $records;
        $results['csvqry'] = $csv_qry_str;
        return $results;
    }

    public function get_details($recid)
    //Query the database for a single record to show details
    {
        $this->db->select('recid AS "Record ID",sppName AS "Taxon Name",siteName AS "Site Name",
                recFullgrid AS "Full Grid Ref",recTetrad AS "Tetrad",
                recRecorders AS "Recorders",recFulldate AS "Full Date",
                recYear AS "Year",recComment As "Comment"');
        $this->db->from('records');
        $this->db->join('species', 'species.sppid = records.recSpecies_id', 'INNER');
        $this->db->join('sites', 'sites.siteid = records.recSite_id', 'INNER');
        // $this->db->join('person', 'person.prsid = records.recColl_id','INNER');
        $this->db->where('recid', $recid);
        $query = $this->db->get();
        return $query->row_array(); //Note this is a single row hence row_array
    }

    /**
     * This is to get the options list for the Taxon Group select input
     * in the start form
     */
    public function get_groups()
    {
        //First query the database table "group"
        $this->db->select('grpid');
        $this->db->select('grpName');
        $this->db->from('group');
        $query = $this->db->get();
        //Then add grpid and grpName as key|value pairs to the array $groups
        $groups = array();
        foreach ($query->result_array() as $row) {
            $groups[$row['grpid']] = $row['grpName'];
        }
        return $groups;
    }

    public function get_species($name_type, $spp_name, $group_id, $code, $axio)
    /*Get a list of species. This may be all species starting with a
    chosen letter (Code 0); all species starting with the letter A (this
    is the default when 'all species' checkbox on the start form
    is selected (Code 1); or all species containing a sub-string entered
    under 'enter a species name or part fo a name' (Code 2) */
    {

        $this->db->select('sppid');
        $this->db->select('sppName');
        $this->db->select('sppCommon');
        $this->db->select('sppAxio');

        $this->db->select('grpName');
        $this->db->from('species');
        //Join group and species tables so that I get the group name rather than group id
        $this->db->join('group', 'group.grpid = species.sppGroup');

        //Filter on the group id passed from the start page group drop-down
        if ($group_id != 0) {
            $this->db->where('sppGroup', $group_id);
        }

        if ($axio == '1') {
            $this->db->where('sppAxio', $axio);
        }

        switch ($code) {
            case 0:
                $this->db->like($name_type, $spp_name, 'after');
                //In this case $spp_name == an initial letter, from the alphabetic menu
                break;
            case 1:
                $this->db->like($name_type, 'A', 'after');
                //'Browse All species' has been selected and we open with all those starting with 'A'
                break;
            case 2:
                $this->db->like($name_type, $spp_name);
                //All or part of a species name has been entered
                break;
        } // End Switch

        //Order by Latin name or common name depending on which is specified in $name_type
        if ($name_type == 'sppCommon') {
            $this->db->order_by('sppCommon asc,sppName asc');
        } else {
            $this->db->order_by($name_type, 'asc');
        }

        $query = $this->db->get();
        //Iterate through the results and copy to a new array $species_list,
        // converting the sppName value in each row to a link  giving all records
        // for that species.
        $species_list = array();
        foreach ($query->result_array() as $row) {
            $row['sppName'] = anchor('shrops/showrecords/species/' . $row['sppid'], $row['sppName'], 'title="Records for Species"');

            if ($row['sppAxio'] == '1') {
                $row['sppAxio'] = 'Yes';
            } else {
                $row['sppAxio'] = 'Unknown';
            }

            unset($row['sppid']);

            $species_list[] = $row;

        }
        //$species_list gets displayed in a table in the shrops/species View
        return $species_list;
    }

    public function get_tetrads_for_species($spid)
    {
        //This is used to populate tetrad maps

        $this->db->select('recYear');
        $this->db->select('recTetrad');
        $this->db->where('recSpecies_id', $spid);
        $this->db->order_by('recYear asc');
        $query = $this->db->get('records');
        $coords = getxy($query);

        return $coords;

    }

    public function get_species_name($spid)
    {
        //Get a single species name for the header of 'records-for-species'
        $this->db->select('sppid');
        $this->db->select('sppName');
        $this->db->select('sppCommon');
        $this->db->where('sppid', $spid);

        $query = $this->db->get('species');

        return $query->row_array();

    } //End of get_species_name

    public function get_site_name($siteid)
    {
        //Get a single site name for the header of 'records-for-sites'
        $this->db->select('siteid');
        $this->db->select('siteName');

        $this->db->where('siteid', $siteid);

        $query = $this->db->get('sites');

        return $query->row_array();

    } //End of get_site_name

    public function get_tetrecs_for_species($spid, $tetrad)
    {
        //Get all records of a species in a tetrad - returned from a
        //tetrad map click
        $this->db->select('siteName AS "Site Name"');
        $this->db->select('recFullgrid AS "Full Grid Ref"');
        $this->db->select('recRecorders AS "Recorders"');
        $this->db->select('recYear AS "Year"');
        $this->db->join('sites', 'sites.siteid = records.recSite_id', 'INNER');
        //$this->db->join('person', 'person.prsid = records.recColl_id','INNER');
        $this->db->where('recSpecies_id', $spid);
        $this->db->where('recTetrad', $tetrad);

        $query = $this->db->get('records');

        return $query->result_array();

    }

    public function get_sites($site_name, $code)
    /*Get a list of sites. This may be all sites starting with a
    chosen letter (Code 0); all sites starting with the letter A (this
    is the default when 'all sites' checkbox on the start form
    is selected (Code 1); or all sites containing a sub-string entered
    under 'enter a site name or part fo a name' (Code 2) */
    {

        $this->db->select('siteid');
        $this->db->select('siteName');

        switch ($code) {
            case 0:
                $this->db->like('siteName', $site_name, 'after');
                break;
            case 1:
                $this->db->like('siteName', 'A', 'after'); //Problem - what if none start with A!
                break;
            case 2:
                $this->db->like('siteName', $site_name);
                break;
        } // End Switch

        $this->db->order_by('siteName', 'asc');
        $query = $this->db->get('sites');
        //Next iterate through the results and copy to a new array $species_list,
        // converting the sppName value in each row to a link  giving all records
        // for that species
        $sites_list = array();
        foreach ($query->result_array() as $row) {
            $row['siteName'] = anchor('shrops/showrecords/sites/' . $row['siteid'], $row['siteName'], 'title="Records for Sites"');
            $sites_list[] = $row;
        }
        return $sites_list;
    }

    public function get_tetrads($tetrad, $limit, $offset)
    {
        $this->db->limit($limit, $offset);
        $this->db->select('tetrad_id');
        $this->db->like('tetrad_id', $tetrad);
        $this->db->order_by('tetrad_id', 'asc');
        $query = $this->db->get('tetrads');
        $tetrad_list = array();
        foreach ($query->result_array() as $row) {
            $row['tetrad_id'] = anchor('shrops/showrecords/tetrads/' . $row['tetrad_id'], $row['tetrad_id'], 'title="Records for Tetrad"');
            $tetrad_list[] = $row;
        }

        return $tetrad_list;

    }

    public function get_rec_count_species($spid)
    {
        //Get count of records matching species id
        $this->db->select('recid');
        $this->db->from('records');
        $this->db->where('recSpecies_id', $spid);
        $count_query = $this->db->get();

        return ($count_query->num_rows());
    }

    public function get_rec_count_sites($site)
    {
        //Get count of records matching site id
        $this->db->select('recid');
        $this->db->from('records');
        $this->db->where('recSite_id', $site);
        $count_query = $this->db->get();

        return ($count_query->num_rows());
    }

    public function get_rec_count_tetrads($tetrad)
    {
        //Get count of records matching tetrad
        $this->db->select('recid');
        $this->db->from('records');
        $this->db->where('recTetrad', $tetrad);
        $count_query = $this->db->get();

        return ($count_query->num_rows());
    }

    public function run_db_query($query)
    {

        return ($this->db->query($query));

    }
}
