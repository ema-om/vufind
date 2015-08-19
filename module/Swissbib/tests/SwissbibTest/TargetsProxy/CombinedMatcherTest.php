<?php
/**
 * CombinedMatcherTest
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 1/2/13
 * Time: 4:09 PM
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
 * @package  SwissbibTest_TargetsProxy
 * @author   Guenter Hipler  <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace SwissbibTest\TargetsProxy;

use SwissbibTest\TargetsProxy\TargetsProxyTestCase;

/**
 * Test detection of targets from combined match patterns (IP + URL)
 *
 * @category Swissbib_VuFind2
 * @package  SwissbibTest_TargetsProxy
 * @author   Guenter Hipler  <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class CombinedMatcherTest extends TargetsProxyTestCase
{
    /**
     * Setup
     *
     * @return void
     */
    public function setUp()
    {
        $path = getcwd() . '/SwissbibTest/TargetsProxy';
        $this->initialize($path . '/config_detect_combined.ini');
    }

    /**
     * Test single IP address to NOT match
     *
     * @return void
     */
    public function testBothFail()
    {
        $proxyDetected = $this->targetsProxy->detectTarget('1.2.3.4', 'swiishbiib.ch');

        $this->assertInternalType('bool', $proxyDetected);
        $this->assertFalse($proxyDetected);
    }

    /**
     * Test single hostname
     *
     * @return void
     */
    public function testUrlSb()
    {
        $proxyDetected = $this->targetsProxy->detectTarget('200.20.0.4', 'swsb');

        $this->assertInternalType('bool', $proxyDetected);
        $this->assertTrue($proxyDetected);
        $this->assertEquals('Target_Both_Match', $this->targetsProxy->getTargetKey());
        $this->assertEquals('apiKeyBothMatch', $this->targetsProxy->getTargetApiKey());
    }
}
