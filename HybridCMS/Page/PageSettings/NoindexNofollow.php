<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class NoindexNofollow
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class NoindexNofollow implements \HybridCMS\Page\PageSettings\IPageSetting {

    /**
     * toString
     *
     * @return String
     */
    public function toString() {
        return "<meta name='robots' content='noindex, nofollow' />";
    }

}

?>