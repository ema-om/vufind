<?php
/**
 * PluginFactory
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
 * @package  VuFind_Search_Params
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */

namespace Swissbib\VuFind\Search\Params;

use Zend\ServiceManager\ServiceLocatorInterface;

use VuFind\Search\Params\PluginFactory as VuFindParamsPluginFactory;

use Swissbib\VuFind\Search\Helper\ExtendedSolrFactoryHelper;
use Swissbib\VuFind\Search\Solr\Params;

/**
 * PluginFactory
 *
 * @category Swissbib_VuFind2
 * @package  VuFind_Search_Params
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
class PluginFactory extends VuFindParamsPluginFactory
{
    /**
     * CanCreateServiceWithName
     *
     * @param ServiceLocatorInterface $serviceLocator ServiceLocatorInterface
     * @param String                  $name           Name
     * @param String                  $requestedName  RequestedName
     *
     * @return mixed
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator,
        $name, $requestedName
    ) {
        /**
         * ExtendedSolrFactoryHelper
         *
         * @var ExtendedSolrFactoryHelper $extendedTargetHelper
         */
        $extendedTargetHelper = $serviceLocator->getServiceLocator()
            ->get('Swissbib\ExtendedSolrFactoryHelper');

        $this->defaultNamespace = $extendedTargetHelper
            ->getNamespace($name, $requestedName);

        return parent::canCreateServiceWithName(
            $serviceLocator, $name, $requestedName
        );
    }

    /**
     * Create a service for the specified name.
     *
     * @param ServiceLocatorInterface $serviceLocator Service locator
     * @param String                  $name           Name of service
     * @param String                  $requestedName  Unfiltered name of service
     *
     * @return Params
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator,
        $name, $requestedName
    ) {
        $options   = $serviceLocator->getServiceLocator()
            ->get('VuFind\SearchOptionsPluginManager')->get($requestedName);

        $className = $this->getClassName($name, $requestedName);

        return new $className(
            $options,
            $serviceLocator->getServiceLocator()->get('VuFind\Config'),
            $serviceLocator->getServiceLocator()->get(
                'Swissbib\FavoriteInstitutions\Manager'
            )
        );
    }
}
