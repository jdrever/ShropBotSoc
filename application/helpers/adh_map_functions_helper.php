<?php
function getatenkm($ntenkm)
//Converts a numeric ten km grid ref e.g. 2269 to an alphanumeric one e.g. SN69
//Wales

{
    $numprefix = substr($ntenkm, 0, 2);

    if ($numprefix == "22") {
        $alphaprefix = "SN";
    } elseif
    ($numprefix == "23") {
        $alphaprefix = "SH";
    } elseif
    ($numprefix == "33") {
        $alphaprefix = "SJ";
    } elseif
    ($numprefix == "12") {
        $alphaprefix = "SM";
    } elseif
    ($numprefix == "32") {
        $alphaprefix = "SO";
    } elseif
    ($numprefix == "11") {
        $alphaprefix = "SR";
    } elseif
    ($numprefix == "21") {
        $alphaprefix = "SS";
    } elseif
    ($numprefix == "31") {
        $alphaprefix = "ST";
    }

//England & Scotland
    elseif
    ($numprefix == "00") {
        $alphaprefix = "SV";
    } elseif
    ($numprefix == "10") {
        $alphaprefix = "SW";
    } elseif
    ($numprefix == "20") {
        $alphaprefix = "SX";
    } elseif
    ($numprefix == "30") {
        $alphaprefix == "SY";
    } elseif
    ($numprefix == "40") {
        $alphaprefix = "SZ";
    } elseif
    ($numprefix == "50") {
        $alphaprefix = "TV";
    } elseif
    ($numprefix == "41") {
        $alphaprefix = "SU";
    } elseif
    ($numprefix == "51") {
        $alphaprefix = "TQ";
    } elseif
    ($numprefix == "61") {
        $alphaprefix = "TR";
    } elseif
    ($numprefix == "42") {
        $alphaprefix = "SP";
    } elseif
    ($numprefix == "52") {
        $alphaprefix = "TL";
    } elseif
    ($numprefix == "62") {
        $alphaprefix = "TM";
    } elseif
    ($numprefix == "43") {
        $alphaprefix = "SK";
    } elseif
    ($numprefix == "53") {
        $alphaprefix = "TF";
    } elseif
    ($numprefix == "63") {
        $alphaprefix = "TG";
    } elseif
    ($numprefix == 24) {
        $alphaprefix = "SC";
    } elseif
    ($numprefix == "34") {
        $alphaprefix = "SD";
    } elseif
    ($numprefix == "44") {
        $alphaprefix = "SE";
    } elseif
    ($numprefix == "54") {
        $alphaprefix = "TA";
    } elseif
    ($numprefix == "25") {
        $alphaprefix = "NX";
    } elseif
    ($numprefix == "35") {
        $alphaprefix = "NY";
    } elseif
    ($numprefix == "45") {
        $alphaprefix = "NZ";
    } elseif
    ($numprefix == "16") {
        $alphaprefix = "NR";
    } elseif
    ($numprefix == "26") {
        $alphaprefix = "NS";
    } elseif
    ($numprefix == "36") {
        $alphaprefix = "NT";
    } elseif
    ($numprefix == "46") {
        $alphaprefix = "NU";
    } elseif
    ($numprefix == "07") {
        $alphaprefix = "NL";
    } elseif
    ($numprefix == "17") {
        $alphaprefix = "NM";
    } elseif
    ($numprefix == "27") {
        $alphaprefix = "NN";
    } elseif
    ($numprefix == "37") {
        $alphaprefix = "NO";
    } elseif
    ($numprefix == "08") {
        $alphaprefix = "NF";
    } elseif
    ($numprefix == "18") {
        $alphaprefix = "NG";
    } elseif
    ($numprefix == "28") {
        $alphaprefix = "NH";
    } elseif
    ($numprefix == "38") {
        $alphaprefix = "NJ";
    } elseif
    ($numprefix == "48") {
        $alphaprefix = "NK";
    } elseif
    ($numprefix == "09") {
        $alphaprefix = "NA";
    } elseif
    ($numprefix == "19") {
        $alphaprefix = "NB";
    } elseif
    ($numprefix == "29") {
        $alphaprefix = "NC";
    } elseif
    ($numprefix == "39") {
        $alphaprefix = "ND";
    }

//$atenkm= str_replace($numprefix,$alphaprefix,$ntenkm); - this causes errors e.g. 3333 becomes SJSJ rather than SJ33
    $atenkm = $alphaprefix . substr($ntenkm, 2, 2);

    return ($atenkm);
}

function getntenkm($atenkm)
//Converts an alphanumeric ten km grid ref e.g. SN69 to a numeric one e.g. 2269
//Only for Wales!!!
//Might be better of modified to take and return an array and so that looping takes place
//inside the function
{
    $alphaprefix = strtoupper(substr($atenkm, 0, 2));

    if

    ($alphaprefix == "SJ") {
        $nprefix = 33;
    } elseif
    ($alphaprefix == "SO") {
        $nprefix = 32;
    }

    $ntenkm = str_replace($alphaprefix, $nprefix, $atenkm);

    return ($ntenkm);
}

function gettetradcoords($atenkm)
{
    $tetlet = substr($atenkm, 4, 1);

    if ($tetlet == "A") {
        $tetcoord = "11";
    } elseif ($tetlet == "B") {
        $tetcoord = "13";
    } elseif ($tetlet == "C") {
        $tetcoord = "15";
    } elseif ($tetlet == "D") {
        $tetcoord = "17";
    } elseif ($tetlet == "E") {
        $tetcoord = "19";
    } elseif ($tetlet == "F") {
        $tetcoord = "31";
    } elseif ($tetlet == "G") {
        $tetcoord = "33";
    } elseif ($tetlet == "H") {
        $tetcoord = "35";
    } elseif ($tetlet == "I") {
        $tetcoord = "37";
    } elseif ($tetlet == "J") {
        $tetcoord = "39";
    } elseif ($tetlet == "K") {
        $tetcoord = "51";
    } elseif ($tetlet == "L") {
        $tetcoord = "53";
    } elseif ($tetlet == "M") {
        $tetcoord = "55";
    } elseif ($tetlet == "N") {
        $tetcoord = "57";
    } elseif ($tetlet == "P") {
        $tetcoord = "59";
    } elseif ($tetlet == "Q") {
        $tetcoord = "71";
    } elseif ($tetlet == "R") {
        $tetcoord = "73";
    } elseif ($tetlet == "S") {
        $tetcoord = "75";
    } elseif ($tetlet == "T") {
        $tetcoord = "77";
    } elseif ($tetlet == "U") {
        $tetcoord = "79";
    } elseif ($tetlet == "V") {
        $tetcoord = "91";
    } elseif ($tetlet == "W") {
        $tetcoord = "93";
    } elseif ($tetlet == "X") {
        $tetcoord = "95";
    } elseif ($tetlet == "Y") {
        $tetcoord = "97";
    } elseif ($tetlet == "Z") {
        $tetcoord = "99";
    }

    return $tetcoord;

}

function getatetrad($numtetrad)
{
    switch ($numtetrad) {
        case "00":$tetlet = "A";
            break;
        case "10":$tetlet = "A";
            break;
        case "01":$tetlet = "A";
            break;
        case "11":$tetlet = "A";
            break;

        case "02":$tetlet = "B";
            break;
        case "12":$tetlet = "B";
            break;
        case "03":$tetlet = "B";
            break;
        case "13":$tetlet = "B";
            break;

        case "04":$tetlet = "C";
            break;
        case "14":$tetlet = "C";
            break;
        case "05":$tetlet = "C";
            break;
        case "15":$tetlet = "C";
            break;

        case "06":$tetlet = "D";
            break;
        case "16":$tetlet = "D";
            break;
        case "07":$tetlet = "D";
            break;
        case "17":$tetlet = "D";
            break;

        case "08":$tetlet = "E";
            break;
        case "18":$tetlet = "E";
            break;
        case "09":$tetlet = "E";
            break;
        case "19":$tetlet = "E";
            break;

        case "20":$tetlet = "F";
            break;
        case "30":$tetlet = "F";
            break;
        case "21":$tetlet = "F";
            break;
        case "31":$tetlet = "F";
            break;

        case "22":$tetlet = "G";
            break;
        case "32":$tetlet = "G";
            break;
        case "23":$tetlet = "G";
            break;
        case "33":$tetlet = "G";
            break;

        case "24":$tetlet = "H";
            break;
        case "34":$tetlet = "H";
            break;
        case "25":$tetlet = "H";
            break;
        case "35":$tetlet = "H";
            break;

        case "26":$tetlet = "I";
            break;
        case "36":$tetlet = "I";
            break;
        case "27":$tetlet = "I";
            break;
        case "37":$tetlet = "I";
            break;

        case "28":$tetlet = "J";
            break;
        case "38":$tetlet = "J";
            break;
        case "29":$tetlet = "J";
            break;
        case "39":$tetlet = "J";
            break;

        case "40":$tetlet = "K";
            break;
        case "50":$tetlet = "K";
            break;
        case "41":$tetlet = "K";
            break;
        case "51":$tetlet = "K";
            break;

        case "42":$tetlet = "L";
            break;
        case "52":$tetlet = "L";
            break;
        case "43":$tetlet = "L";
            break;
        case "53":$tetlet = "L";
            break;

        case "44":$tetlet = "M";
            break;
        case "54":$tetlet = "M";
            break;
        case "45":$tetlet = "M";
            break;
        case "55":$tetlet = "M";
            break;

        case "46":$tetlet = "N";
            break;
        case "56":$tetlet = "N";
            break;
        case "47":$tetlet = "N";
            break;
        case "57":$tetlet = "N";
            break;

        case "48":$tetlet = "P";
            break;
        case "58":$tetlet = "P";
            break;
        case "49":$tetlet = "P";
            break;
        case "59":$tetlet = "P";
            break;

        case "60":$tetlet = "Q";
            break;
        case "70":$tetlet = "Q";
            break;
        case "61":$tetlet = "Q";
            break;
        case "71":$tetlet = "Q";
            break;

        case "62":$tetlet = "R";
            break;
        case "72":$tetlet = "R";
            break;
        case "63":$tetlet = "R";
            break;
        case "73":$tetlet = "R";
            break;

        case "64":$tetlet = "S";
            break;
        case "74":$tetlet = "S";
            break;
        case "65":$tetlet = "S";
            break;
        case "75":$tetlet = "S";
            break;

        case "66":$tetlet = "T";
            break;
        case "76":$tetlet = "T";
            break;
        case "67":$tetlet = "T";
            break;
        case "77":$tetlet = "T";
            break;

        case "68":$tetlet = "U";
            break;
        case "78":$tetlet = "U";
            break;
        case "69":$tetlet = "U";
            break;
        case "79":$tetlet = "U";
            break;

        case "80":$tetlet = "V";
            break;
        case "90":$tetlet = "V";
            break;
        case "81":$tetlet = "V";
            break;
        case "91":$tetlet = "V";
            break;

        case "82":$tetlet = "W";
            break;
        case "92":$tetlet = "W";
            break;
        case "83":$tetlet = "W";
            break;
        case "93":$tetlet = "W";
            break;

        case "84":$tetlet = "X";
            break;
        case "94":$tetlet = "X";
            break;
        case "85":$tetlet = "X";
            break;
        case "95":$tetlet = "X";
            break;

        case "86":$tetlet = "Y";
            break;
        case "96":$tetlet = "Y";
            break;
        case "87":$tetlet = "Y";
            break;
        case "97":$tetlet = "Y";
            break;

        case "88":$tetlet = "Z";
            break;
        case "98":$tetlet = "Z";
            break;
        case "89":$tetlet = "Z";
            break;
        case "99":$tetlet = "Z";
            break;

    }
    return $tetlet;
}

function getxy($disresult)
//This is for drawing the tetrad dots for species
//To return a 2-D array containing x and y coordinates on wmap.gif for dots
//corresponding to tetrad Sqs
//modified for Codeigniter in lines 430-435
{
    $xmin = 60; //=320 KM EAST
    $xmax = 399; //=370KM EAST
    $ymin = 204; //=320KM NORTH
    $ymax = 543; //= 270KM NORTH
    /*The above represent a range in pixels corresponding to 50m north and south
    on the Shropshire map shrops.png and are used to calculate conversion
    factors $feast and $fnorth*/

    $xoriginpx = 22; //25; /*The map x-origin in pixels on shrops.png*/
    $xoriginkm = 314.11; /*The map x-origin in km on shrops.png*/
    $yoriginpx = 577; //693; /*The map y-origin in pixels on shrops.png*/
    $yoriginkm = 265; /*The map y-origin in km on shrops.png*/

    $feast = ($xmax - $xmin) / 50;
    $fnorth = ($ymax - $ymin) / 50;
    /*factors corresponding to number of pixels on map gif
    corresponding to 1km*/

    $i = 0;
// while ($row = mysql_fetch_row($disresult))

    foreach ($disresult->result() as $row) {
        // if ($row->recTetrad != "")
        if (strlen($row->recTetrad) == 5) //i.e. is a valid tetrad - not a complete check though!
        {
            $tenkm_num = getntenkm($row->recTetrad);
            $tetcoords = gettetradcoords($row->recTetrad);
            $year[$i] = $row->recYear;

            $EastStr = substr($tenkm_num, 0, 1) . substr($tenkm_num, 2, 1) . substr($tetcoords, 0, 1);
            $NorthStr = substr($tenkm_num, 1, 1) . substr($tenkm_num, 3, 1) . substr($tetcoords, 1, 1);

            $East = intval($EastStr);
            $North = intval($NorthStr);

            $x[$i] = intval((($East - $xoriginkm) * $feast) + $xoriginpx);
            $y[$i] = intval($yoriginpx - (($North - $yoriginkm) * $fnorth));

            $i = $i + 1;
        }
    }
    $xy = array('x' => $x, 'y' => $y, 'year' => $year);
    return ($xy);
}

function get_tetrad_from_map($map_x, $map_y)
{
    $xmin = 60; //=320 KM EAST
    $xmax = 399; //=370KM EAST
    $ymin = 204; //=320KM NORTH
    $ymax = 543; //= 270KM NORTH
    /*The above represent a range in pixels corresponding to 50m north and south
    on the Shropshire map shrops.png and are used to calculate conversion
    factors $feast and $fnorth*/

    $xoriginpx = 22; /*The map x-origin in pixels on shrops.png*/
    $xoriginkm = 314.11; /*The map x-origin in km on shrops.png*/
    $yoriginpx = 577; /*The map y-origin in pixels on shrops.png*/
    $yoriginkm = 265; /*The map y-origin in km on shrops.png*/

    $feast = ($xmax - $xmin) / 50; //Conversion factors
    $fnorth = ($ymax - $ymin) / 50;
    //get easting and northing as strings, inserting any necessary leading zeroes
    $easting = strval(intval(($map_x - $xoriginpx) * (1 / $feast)) + $xoriginkm);
    // if (strlen($easting) == 2) $easting = "0" . $easting;
    // if (strlen($northing) == 1) $northing = "00" . $northing;
    $northing = strval(intval(($yoriginpx - $map_y) * (1 / $fnorth)) + $yoriginkm);
    // if (strlen($northing) == 2) $northing = "0" . $northing;
    // if (strlen($northing) == 1) $northing = "00" . $northing;

    //Get a representation of the 10km sq grid reference with a numeric 100km sq label
    //e.g. 2258 is equivalent to SN58
    $numtenkm = substr($easting, 0, 1) . substr($northing, 0, 1)
    . substr($easting, 1, 1) . substr($northing, 1, 1);

    $numtetrad = substr($easting, 2, 1) . substr($northing, 2, 1);
    //echo "easting = " . $easting . ", northing = " . $northing . ", numtetrad = " . $numtetrad;
    $tetradlet = getatetrad($numtetrad);

    //Get the alphanumeric equivalent of the 10km sq
    $atenkm = getatenkm($numtenkm);
    $tetrad = $atenkm . $tetradlet;

    return $tetrad;
}
