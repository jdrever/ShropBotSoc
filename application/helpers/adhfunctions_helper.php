<?php
//Version 27/01/04 - 18.23 This version has call to query_to_csv removed
function safe_query ($query = "")
{
	global	$query_debug;

	if (empty($query)) { return FALSE; }

	if (!empty($query_debug)) { print "<pre>$query</pre>\n"; }

	$result = mysql_query($query)
		or die("Query failed!!: "
			."<li>errorno=".mysql_errno()
			."<li>error=".mysql_error()
			."<li>query=".$query
		);
	return $result;
}

function striplimit($query)
{
  $limitpos = strpos($query, "LIMIT");
	
  if ($limitpos != 0)
  {
    $midquery = substr($query, $limitpos );
    $newquery = str_replace($midquery," ",$query);
    return $newquery;
  }
	else
	{
	  return $query;
	}
}

function query_to_csv($query,$num_cols_to_show)
//Generate and write a csv file from the given query

{
	$newquery = striplimit($query);
  //echo"Query: " . $newquery;
	$result=safe_query($newquery);
	$num_rows = mysql_num_rows($result);
    $fp = fopen("shrops.csv", "w+") or die("Could not open csv file for writing");
  //echo "File opened!!!";
	while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//Loop through the results rows
	{
	   
	 	 for ($i=0; $i<$num_cols_to_show; $i++)
		 {
			list($key,$value)=each($row);//Get key, value pair for each row
			 $fout = fwrite($fp, $value);
			//debug if ($i < $num_cols_to_show-1)
	          $fout = fwrite($fp, ",");
					
			 }//End of looping through fields in each row

		$fout = fwrite($fp, "\n");
	}//End of looping through result rows
	
     fclose($fp);
	
    mysql_free_result($result);
	return $num_rows; //this is new at 11 Oct 07
}
//END OF QUERY_TO_CSV()


function select_to_table($query, $num_cols_to_show)
/*
This function is passed a query string and generates an html table. 
$num_cols_to_show determines how many of the columns/fields generated in the
query should appear in the table - for example 5 will mean the first 5 fields 
are shown. This means it is important in which order the fields are requested 
in the query. Note that this means you can generate query results which you might
not actually wish to display, like numeric ids.

The function also generates links for specific fields - e.g. if 'species_name'
is amongst the fields then a link is generated which includes the species id and
a vc code to pass to another code block generating a new query.

For each record a link associated with an arrow gif is generated in the first column of 
the table. This links to form.php which show the entire record in a form view.
*/
{
	global $PHP_SELF;//Must go here for $PHP_SELF
	
  $result=safe_query($query);
	$num_rows = mysql_num_rows($result);
	if ($num_rows == 0) 
	{
	 	echo "<h2 align = 'center'>No matching records were found</h2>";
	}
	else
	{
	//query_to_csv($query, $num_cols_to_show);
	
	 //$fp = fopen("wtpdb.txt", "w+");
		
		
	  $total_num_cols = mysql_num_fields($result);
		//Required so we can loop through to check for specific fields
	
		
		//This is the value returned by the function
	
		//Layout table header
	
		echo "<table border = 0 cellspacing = 2 cellpadding = 5 align = center>\n";
		echo "<tr align= center bgcolor = '#ccccff'>\n";
	

//Display header for the first column which is always the one with 
//with the arrow gif symbol. In this header the yellow full-stop is invisible
//and is just there to ensure cell bgcolor shows in Netscape

	 echo "<th width = 10><font color = '#ccccff'>.</font></th>\n";
	 
//Display the rest of the headers 

	for ($i=0;$i<$num_cols_to_show; $i++)
	{
	 	 echo "<th>" . mysql_field_name($result, $i) . "</th>\n";
	}
	echo "</tr>\n";
	
//End of table headers

//Layout table body
	$count = 0;
	while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//Loop through the results
	{
	 	 $count = $count +1;
     $mod = $count % 2;//Is the row number odd or even?   
		 echo "<tr align = left"; 
		 if ($mod) //It's even
  	 {
   	 	  echo " bgcolor =#CCCCCC";//Row background is one colour
		 }
		 else //It's odd.
  	 {
		 	  echo " bgcolor =#99CCCC";//Row background is a different colour
		 }
	
	   echo ">\n";//Close the row tag and put a line-break in the html code

//In the first column construct a link to the record form view page using an arrow gif
	  
		 $recstring   = sprintf("<a href=\"%s?recid=%s\"><img src='images/arrow.gif' border = '0' alt='Show record details'></a>" 
			            , "sb_form.php",$row["Rec ID"] );
		 echo "<td align = 'center'>$recstring </td>";

//Run through the remaining field names and if one requiring a link, construct the link.
//Also set standard column widths for each field so that they don't vary with the
//field value (this looks horrible if you have a lage number of records and 
//page through them using prev_next() - the table structure jumps around!!
			
		 for ($i=0; $i<$num_cols_to_show; $i++)
			 {
			    list($key,$value)=each($row);//Get key, value pair for each row
					
					if ($key=="Site Name")
					{
					
					   
					   echo "<td width = 250>";
						//  if ($value == "Shropshire")
						// echo $value;
						// else
						// {
						
			   		 $ststring = sprintf("<a href=\"%s?siteid=%s\">%s</a>"
		     		 					 , $PHP_SELF,$row["Site ID"],$value ); 
				     echo $ststring;
						 }
	    	//  }
				  elseif ($key=="Recorder")
					{
					   echo "<td width = 180>";
			    	 $recdrstring = sprintf("<a href=\"%s?peepid=%s\">%s</a>"
						              , $PHP_SELF, $row["Recorder ID"],$value );
		         echo $recdrstring;
	    		}
				  elseif ($key=="Species Name")
					{
					   echo "<td width = 280>";
			    	 $specstring = sprintf("<a href='%s?spid=%s'>%s  </a>"
						             ,$PHP_SELF,$row["Species ID"], $value );
		         echo $specstring;
	    		}
				  elseif ($key=="Grid Ref")
					{
				    echo "<td width = 100>";
				  	echo $value;
				  }
					elseif ($key=="VC")
					{
				    echo "<td width = 20>";
					 	echo $value;
					}
					elseif ($key=="Year")
					{
					   echo "<td width = 40>";
					   echo $value;
					}
					else
					{
					 	echo "<td width = 50>";
					  echo $value;
					}
					echo "</td>\n";
					
			//	$fout = fwrite($fp, $value);
			//	if ($i < $num_cols_to_show-1)
			//  $fout = fwrite($fp, ",");
					
			 }//End of looping through fields in each row
			 
			 echo "</tr>\n";//Close row tag and insert line break
		//	$fout = fwrite($fp, "\n");
	}//End of looping through result rows
	
	echo "</table>";
	
 //fclose($fp);
	
	}
return $num_rows;
mysql_free_result($result);
	
}
//END OF SELECT_TO_TABLE()



function prev_next($qstring,$nrows,$offset,$limit,$axio)
//This puts previous/next links on a page to allow paging through a large 
//(i.e.greater than $limit) record set.  NEW VERSION
{
global $PHP_SELF;
echo"<table align = center cellpadding=10>
<tr>
<td width =200>";
if ($offset> 0)
  {
  if ($axio)
    {
    $lesslink = 
    sprintf("<a href=\"%s?%s&offset=%s&axio2=true\">%s  </a>", $PHP_SELF,$qstring,$offset-$limit,'&lt;&lt;Previous ' . $limit . ' Records' );

   }
  else
   {
  $lesslink=
 sprintf("<a href=\"%s?%s&offset=%s\">%s  </a>", $PHP_SELF,$qstring,$offset-$limit,'&lt;&lt;Previous ' . $limit . ' Records' );
   }
echo"$lesslink";
}


echo"</td>
<td width = 200 align=right>";

if (!($nrows < $limit))
 {
  if ($axio)
  
{
$morelink
= sprintf("<a href=\"%s?%s&offset=%s&axio2=true\">%s  </a>", $PHP_SELF,$qstring,$offset+$limit,'Next ' . $limit . ' Records&gt;&gt;' );
}
else
{
 $morelink
= sprintf("<a href=\"%s?%s&offset=%s\">%s  </a>", $PHP_SELF,$qstring,$offset+$limit,'Next ' . $limit . ' Records&gt;&gt;' );
}
echo"$morelink";
}
echo"</td></tr></table>";
}



?>
