<?php
/**
 * LastSearchWord
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
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
 * @package  View_Helper
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
namespace Swissbib\View\Helper;

use Zend\View\Helper\AbstractHelper;
use VuFind\Search\Memory;

/**
 * Get last search word from search memory
 * the search word (lookfor) is part of the search URL
 *
 * @category Swissbib_VuFind2
 * @package  View_Helper
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
class LastSearchWord extends AbstractHelper
{
    /**
     * Get last search word
     *
     * @return String
     */
    public function __invoke()
    {
        $lookFor       = '';
        $lastSearchUrl = $this->getView()->plugin('getextendedlastsearchlink')
            ->getLinkOnly();
        $lastSearch    = parse_url($lastSearchUrl);

        if (isset($lastSearch['query'])) {
            parse_str($lastSearch['query'], $queryParts);

            if (isset($queryParts['lookfor'])) {
                $lookFor = trim($queryParts['lookfor']);
            }
        }

        return $lookFor;
    }
}
