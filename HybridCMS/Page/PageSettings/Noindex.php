<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class Noindex
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Noindex implements \HybridCMS\Page\PageSettings\IPageSetting {

    /**
     * toString
     *
     * @return String
     */
    public function toString() {
        return "<meta name='robots' content='noindex' />";
    }

}

?>