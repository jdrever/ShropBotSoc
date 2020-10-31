<?php

/**
 * 
 */
function truncateArray($truncateAt, $arr) {
  array_splice($arr, $truncateAt, (count($arr) - $truncateAt));
  return $arr;
}
