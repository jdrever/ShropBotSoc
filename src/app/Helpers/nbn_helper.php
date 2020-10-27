<?php
 
/** 
 * 
 */
function IsNullOrEmptyString($str){
  return (!isset($str) || trim($str) === '');
}

/**
 * 
 */
function truncateArray($truncateAt, $arr) {
  array_splice($arr, $truncateAt, (count($arr) - $truncateAt));
  return $arr;
}