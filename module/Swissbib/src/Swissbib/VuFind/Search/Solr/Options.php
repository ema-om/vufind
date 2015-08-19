<?php
/**
 * Options
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, 2015.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category Swissbib_VuFind2
 * @package  VuFind_Search_Solr
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org  Main Page
 */

namespace Swissbib\VuFind\Search\Solr;

use VuFind\Search\Solr\Options as VuFindSolrOptions;

/**
 * Class to extend the core VF2 SOLR functionality related to Options
 *
 * @category Swissbib_VuFind2
 * @package  VuFind_Search_Solr
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.vufind.org  Main Page
 */
class Options extends VuFindSolrOptions
{
    /**
     * Set default limit
     *
     * @param Integer $limit Limit
     *
     * @return void
     */
    public function setDefaultLimit($limit)
    {
        $this->defaultLimit = intval($limit);
    }

    /**
     * SetDefaultSort
     *
     * @param String $defaultSort DefaultSort
     *
     * @return void
     */
    public function setDefaultSort($defaultSort)
    {
        $this->defaultSort = $defaultSort;
    }

    /**
     * Translate a string if a translator is available.
     * We have to override this method because VF2 core doesn't support
     * multiple Textdomains for translations at the moment
     *
     * @param string $str     String to translate
     * @param array  $tokens  Tokens to inject into the translated string
     * @param string $default Default value to use if no translation is found (null
     *                        for no default).
     *
     * @return string
     */
    public function translate($str, $tokens = [], $default = null)
    {
        return null !== $this->translator ?
            is_array($str) && count($str) == 2 ?
                $this->translator->translate($str[0], $str[1]) :
                    $this->translator->translate($str) : $str;
    }
}
