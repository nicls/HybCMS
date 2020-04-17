<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

//check if AMP is requested to deliver the right content
$isAMPRequested = $hcms->isAmpRequested();

/* CDS include Page Setup   
 ================================================== **/
$setupPath = $objCDS->getPathTo('setup', $isAMPRequested);
if(false === empty($setupPath)) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $setupPath);
}  

/* CDS include Header     
================================================== */
$headerPath = $objCDS->getPathTo('header', $isAMPRequested);
if(false === empty($headerPath)) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $headerPath);
}  

/* CDS include Content     
================================================== */
$contentPath = $objCDS->getPathTo('content', $isAMPRequested);
if(false === empty($contentPath)) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $contentPath);
}  

/* CDS include Footer     
================================================== */
$footerPath = $objCDS->getPathTo('footer', $isAMPRequested);
if(false === empty($footerPath)) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $footerPath);
}  
?>