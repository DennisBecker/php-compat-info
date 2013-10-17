<?php
/**
 * Unit tests for PHP_CompatInfo package, geoip Reference
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    GIT: $Id$
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 2.8.0
 */

require_once 'GenericTest.php';

/**
 * Tests for the PHP_CompatInfo class, retrieving components informations
 * about geoip extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class PHP_CompatInfo_Reference_GeoipTest
    extends PHP_CompatInfo_Reference_GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @covers PHP_CompatInfo_Reference_Geoip::getExtensions
     * @covers PHP_CompatInfo_Reference_Geoip::getFunctions
     * @covers PHP_CompatInfo_Reference_Geoip::getConstants
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$optionalfunctions = array(
            // requires GeoIP C library 1.4.1 or higher (LIBGEOIP_VERSION >= 1004001)
            'geoip_region_name_by_code',
            'geoip_time_zone_by_country_and_region',
        );
        self::$obj = new PHP_CompatInfo_Reference_Geoip();
        parent::setUpBeforeClass();
    }
}
