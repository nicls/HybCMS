<?php if(!defined('ROOTDOC')) die();

/**
 * Default timezone
 */
date_default_timezone_set('Europe/Berlin');

/**
 * Database Settings
 */
define("DBMAIN_NAME", 'root');
define('DBMAIN_PASS', '5iAMuckZaIjJpIBPYRJB');
define('DBMAIN_DB', 'hcms');
define('DBMAIN_HOST', '127.0.0.1');

/**
 * Databsse Settings Memcached
 */
define('MEMCACHED_HOST', '127.0.0.1');
define('MEMCACHED_PORT', '11211');

/**
 * Defines the protocol
 */
define('HYB_PROTOCOL', 'http://');

/**
 * like html, php ... HYB_FILETYPE is the visible 
 * filetype in the adressbar of the browser
 */
define('HYB_FILETYPE', 'html');

/**
 * defines the hostname like hybcms.vs
 */
define('HYB_HOST_NAME', $_SERVER['SERVER_NAME']);

/**
 * defines the homepage like http://hybcms.de
 */
define('HYB_HOMEPAGE', HYB_PROTOCOL . HYB_HOST_NAME);

/**
 * Like X:/NetBeans/HybCMS/
 */
define('HYB_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/");

/**
 * index.php - dateiendung wird durch HYB_FILETYPE ersetzt
 */
define('HYB_CURRDOC', preg_replace('/\..+$/', 
        '.' . HYB_FILETYPE, pathinfo($_SERVER['PHP_SELF'], 
                PATHINFO_BASENAME)));

/**
 * defines the current original filename like index.php 
 */
define('HYB_CURRDOC_ORIGFILETYPE', pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME));

/**
 * defines the public directory structure eg "/top/second/third" -> like /cateins
 */
define('HYB_CURRPATH', str_replace('\\', '', dirname($_SERVER['PHP_SELF'])));


/**
 * Defines the current relative URL like Bsp: /index.html
 */
define('HYB_CURRELURL', 
        str_replace("//", "/", HYB_CURRPATH . '/' . HYB_CURRDOC));

/**
 * Defines the current relative URL with the original not rewitten filetype
 */
define('HYB_CURRELURL_ORIGFILETYPE', 
        str_replace("//", "/", HYB_CURRPATH . '/' . HYB_CURRDOC_ORIGFILETYPE));

/**
 * Defines the current URL like: http://hybcms.vs/cateins/cat-1-1.php,
 * index.filetype gets removed
 */
define('HYB_CURRURL', 
        HYB_PROTOCOL . HYB_HOST_NAME . 
        str_replace("//", "/", HYB_CURRPATH . '/' 
                . str_replace('index.' . HYB_FILETYPE , '', HYB_CURRDOC)));

/**
 * Defines the Plugin-Path
 */
define('HYB_PLUGINPATH', 
        HYB_PROTOCOL . HYB_HOST_NAME . '/HybridCMS/Plugins/');

/**
 * Defines the image Thumbfolder
 */
define('HYB_THUMBFOLDER', '/images/thumbs');

/**
 * Defines Directory for Logging
 */
define('LOGFILE_DIR', HYB_ROOT . 'log');

/**
 * Defines the current language
 */
$arrLanguages = array('de', 'en', 'es', 'it');
foreach ($arrLanguages as $language) 
{
    if(false !== stripos(HYB_CURRELURL, '/'. $language . '/'))
    {
        define('HYB_LANG', $language);
    }
}
if(false === defined('HYB_LANG'))
{
    define('HYB_LANG', 'de');
    
}
?>