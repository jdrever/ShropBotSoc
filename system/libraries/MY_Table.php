<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Table extends CI_Table
{
    var $hide_columns  = array();
    var $format_columns = array();
    var $merge_columns = array();
    var $merged_delimiter = ' ';

    /**
     * Set the columns to merge along with 
     * the spacer that goes in between.
     *
     * @access public
     * @param array
     * @param string
     * @return void
     **/
    function set_columns_to_merge($merge_columns, $merged_delimiter = ' ')
    {
        if(!is_array($merge_columns))return FALSE;
        $this->merge_columns = $merge_columns;

        if(is_null($merged_delimiter))$merged_delimiter = ' ';
        $this->merged_spacer = $merged_delimiter;
    }

    /**
     * Set the columns to hide
     *
     * @access public
     * @param array
     * @return void
     **/
    function set_columns_to_hide($hide_columns)
    {
        if(!is_array($hide_columns))return FALSE;
        $this->hide_columns = $hide_columns;
    }

    /**
     * Set the columns to format
     *
     * @access public
     * @param array with keys being the column names and values being the format function names
     * @return void
     **/    
    function set_columns_to_format($format_columns)
    {
        if(!is_array($format_columns))return FALSE;
        $this->format_columns = $format_columns;
    }

    /**
     * Generate the table format special cells (Enumerations, Dates).
     * Columns can also be merged by specifying the master and slave
     * columns (master => slave)in the merge_column array. The
     * merged_spacer goes in between all merged column values.
     *
     * NOTE: Merged columns always take the header of the
     * master (key) column.
     *
     * @access    public
     * @param    mixed
     * @return    string
     */
    function generate($table_data = NULL)
    {
        // The table data can optionally be passed to this function
        // either as a database result object or an array
        if(!is_null($table_data))
        {
            if(is_object($table_data))$this->_set_from_object($table_data);
            elseif(is_array($table_data))
            {
                $set_heading = !(count($this->heading) == 0 && !$this->auto_heading);
                $this->_set_from_array($table_data, $set_heading);
            }
        }

        // Is there anything to display?  No?  Smite them!
        if(count($this->heading) == 0 AND count($this->rows) == 0)return 'Undefined table data';

        // Compile and validate the template date
        $this->_compile_template();

        // Build the table!

        $out = $this->template['table_open'];
        $out .= $this->newline;        

        // Add any caption here
        if($this->caption)
        {
            $out .= $this->newline;
            $out .= '<caption>' . $this->caption . '</caption>';
            $out .= $this->newline;
        }

        // Is there a table heading to display?
        if(count($this->heading) > 0)
        {
            $out .= $this->template['heading_row_start'];
            $out .= $this->newline;        
            foreach($this->heading as $heading_key => $heading)
            {
                $heading_key = is_object($table_data) ? $heading : $heading_key;

                //Only add non-combined headers (ie master headers or regular headers)
                if(!$this->_merge_column($heading_key) &&
                   !$this->_hide_column($heading_key))
                {
                    $out .= $this->template['heading_cell_start'];
                    $out .= $heading;
                    $out .= $this->template['heading_cell_end'];
                }
            }

            $out .= $this->template['heading_row_end'];
            $out .= $this->newline;                
        }

        // Build the table rows
        if (count($this->rows) > 0)
        {
            $i = 1;

            foreach($this->rows as $row)
            {
                if(!is_array($row))break;

                // We use modulus to alternate the row colors
                $name = fmod($i++, 2) ? '' : 'alt_';

                $out .= $this->template['row_'.$name.'start'];
                $out .= $this->newline;        

                foreach($row as $cell_key => $cell)
                {
                    //Use only non-combined cells (ie master cells, or regular cells)
                    if(!$this->_merge_column($cell_key) &&
                       !$this->_hide_column($cell_key))
                    {
                        $out .= $this->template['cell_'.$name.'start'];

                        if($cell === "" || $cell === null)$out .= $this->empty_cells;

                        $cell = $this->_format_cell($cell_key, $cell);
                        if(array_key_exists($cell_key, $this->merge_columns))
                        {
                            $cell .= $this->merged_delimiter;
                            $cell .= $this->_format_cell($this->merge_columns[$cell_key], $row[$this->merge_columns[$cell_key]]);
                        }
                        $out .= $cell;
                        $out .= $this->template['cell_'.$name.'end'];
                    }
                }

                $out .= $this->template['row_'.$name.'end'];
                $out .= $this->newline;    
            }
        }
        $out .= $this->template['table_close'];
        return $out;
    }

    /**
     * Helper method to format a cell using the specified format_columns array
     *
     * @access private
     * @param string
     * @return string
     **/
    private function _format_cell($cell_key, $cell)
    {
        if(array_key_exists($cell_key, $this->format_columns) &&
       is_callable($this->format_columns[$cell_key]))
        {
            $cell = $this->format_columns[$cell_key]($cell);    
        }
        return $cell;
    }

    /**
     * Determines if the specified column value should be
     * combined with another and thusly not rendered.
     *
     * @access private
     * @param string
     * @return boolean
     **/
    private function _merge_column($column)
    {
        //return if this column isn't a master column or a regular column (its a merged column)
        return !array_key_exists($column, $this->merge_columns) &&
              in_array($column, $this->merge_columns); 
    }

    /**
     * Determines if the specified column value should be hidden
     *
     * @access private
     * @param string
     * @return boolean
     **/
    private function _hide_column($column)
    {
        return in_array($column, $this->hide_columns);
    }
}
?>