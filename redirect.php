<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

$isAdwordsUser = false;
if(true === isset($_SERVER['HTTP_REFERER']) 
   && 
   false === empty($_SERVER['HTTP_REFERER'])) 
{
    //check if user clicked on an adwords ad
    $objSEReferrer = new \HybridCMS\Modules\Url\SEReferrer(
            $_SERVER['HTTP_REFERER']);   
    $isAdwordsUser = $objSEReferrer->refIsFromAdwords();
}

/**
 * Affiliate Id Amazon
 */
$refParam_amazon = "etc=" . sha1(time())
                 . "&ie=UTF8"
                 . "&tag=wk09234-21"
                 . "&hyb=" . sha1(time());

try
{
    $objUrl = null;
    if(true === \HybridCMS\Util\VarCheck::issetAndNotEmpty($_GET['url']))
    {
        $objUrl = new \HybridCMS\Modules\Url\Url($_GET['url']);
    }
    
    echo "<pre>";
    print_r($objUrl);
    echo "</pre>";
    
    if(false === empty($objUrl))
    {
        $scheme = $objUrl->getScheme();       
        $hostname = $objUrl->getHostName();
        $path = $objUrl->getPath();
        
        $url = $scheme . "://" . $hostname . $path;
        
        /**
         * Handle Amazon Links
         */
        if(true === $objUrl->isUrlFromHost("www.amazon.de"))
        {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $url . "?" . $refParam_amazon);
        }
    }
    
} 
catch (\Exception $e) 
{
    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(
            LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

?>
<!doctype html>
<html>
    <head>
     <meta name="robots" content="noindex">
    </head>
    <body>
        <a href="/" title="Zurück zur Startseite">Zurück zur Startseite</a>
    </body>
</html>