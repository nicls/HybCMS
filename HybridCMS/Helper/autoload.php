<?php if(!defined('ROOTDOC')) die();

/** Load Classes dynamicly */
function autoload($className) {
    //echo "Lade Klasse: " . $className . "<br />";
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    //check if file does exist
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $fileName)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/' . $fileName;
    } else {
        throw new \Exception(
                "Error Processing autoload from class-Loader:
                    tryed to load class: " . htmlentities($className) .
                ". File " . $_SERVER['DOCUMENT_ROOT'] . '/' . $fileName . " does not exists.", 1);
    }
}

spl_autoload_register("autoload");
?>