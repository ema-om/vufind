<?php
/**
 * Service used to manage the user registration process using the National Licence registration platform by Switch.
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
 * @package  Services
 * @author   Simone Cogno <scogno@snowflake.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */

namespace Swissbib\Services;


use Swissbib\VuFind\Db\Row\NationalLicenceUser;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SwitchApi implements ServiceLocatorAwareInterface
{
    /**
     * ServiceLocator
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Swissbib configuration.
     *
     * @var array $config
     */
    protected $config;

    /**
     * SwitchApi constructor.
     * @param array $config Swissbib configuration.
     */
    public function __construct($config)
    {
        $this->config = $config['swissbib']['switch_api'];
    }


    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set national-licence-compliant flag to the user.
     *
     * @param $userExternalId
     * @throws \Exception
     */
    public function setNationalCompliantFlag($userExternalId)
    {
        // 1 create a user
        $internalId = $this->createSwitchUser($userExternalId);
        // 2 Add user to the National Compliant group
        $this->addUserToNationalCompliantGroup($internalId);
        // 3 verify that the user in on National Compliant group
        if(!$this->userIsOnNationalCompliantSwitchGroup($userExternalId)) {
            throw new \Exception("Was not possible to add user to the national-licence-compliant group");
        }
    }

    /**
     * Create a user in the National Licenses registration platform.
     *
     * @param string $externalId
     * @return mixed
     * @throws \Exception
     */
    protected function createSwitchUser($externalId)
    {
        $client = $this->getBaseClient(Request::METHOD_POST, "/Users");
        $params = ['externalID' => $externalId];
        $client->setRawBody(json_encode($params, JSON_UNESCAPED_SLASHES));
        /** @var Response $response */
        $response = $client->send();
        $statusCode = $response->getStatusCode();
        $body  = $response->getBody();
        if($statusCode !== 200) {
            throw new \Exception("Status code: $statusCode result: $body");
        }
        $res = json_decode($body);
        return  $res->id;
    }

    /**
     * Get user info from the National Licenses registration platform.
     *
     * @param string $internalId
     * @return mixed
     */
    protected function getSwitchUserInfo($internalId)
    {
        $client = $this->getBaseClient(Request::METHOD_GET, "/Users/" . $internalId);
        $response = $client->send();
        $statusCode = $response->getStatusCode();
        $body  = $response->getBody();
        if($statusCode !== 200) {
            throw new \Exception("Status code: $statusCode result: $body");
        }
        $res = json_decode($body);
        return $res;
    }

    /**
     * Add user to the National Licenses Programme group on the National Licenses registration platform.
     *
     * @param string $userInternalId
     * @throws \Exception
     */
    protected function addUserToNationalCompliantGroup($userInternalId)
    {
        $client = $this->getBaseClient(Request::METHOD_PATCH, "/Groups/" . $this->config['national_licence_programme_group_id']);
        $params = [
            "schemas" => [
                $this->config['schema_patch']
            ],
            "Operations" => [
                [
                    "op" => $this->config['operation_add'],
                    "path" => $this->config['path_member'],
                    "value" => [
                        [
                            '$ref' => $this->config['base_endpoint_url'] . "/Users/" . $userInternalId,
                            "value" => $userInternalId
                        ]
                    ]
                ]
            ]
        ];
        $rawData  = json_encode($params, JSON_UNESCAPED_SLASHES);
        $client ->setRawBody($rawData);
        $response = $client->send();
        $statusCode = $response->getStatusCode();
        $body  = $response->getBody();
        if($statusCode !== 200) {
            throw new \Exception("Status code: $statusCode result: $body");
        }
    }

    /**
     * Check if the user is on the National Licenses Programme group.
     *
     * @param NationalLicenceUser $userExternalId
     * @return bool
     */
    protected function userIsOnNationalCompliantSwitchGroup($userExternalId)
    {
        $internalId = $this->createSwitchUser($userExternalId);
        $switchUser = $this->getSwitchUserInfo($internalId);
        foreach ($switchUser->groups as $group) {
            if($group->value === $this->config['national_licence_programme_group_id']){
                return true;
            }
        }
        return false;
    }

    /**
     * Get an instance of the HTTP Client with some basic configuration.
     *
     * @param string $method
     * @param string $relPath
     * @return Client
     */
    protected function getBaseClient($method = Request::METHOD_GET, $relPath = "")
    {
        $client = new Client($this->config['base_endpoint_url'] . $relPath, [
            'maxredirects' => 0,
            'timeout'      => 30
        ]);
        echo $client->getUri();
        $client->setHeaders([
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ]);
        $client->setMethod($method);
        $client->setAuth($this->config['auth_user'], $this->config['auth_password']);
        return $client;
    }
}