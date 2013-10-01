<?php
/**
 * Unit tests for PHP_CompatInfo package, Libxml Reference
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    GIT: $Id$
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 2.0.0RC4
 */

require_once 'GenericTest.php';

/**
 * Tests for the PHP_CompatInfo class, retrieving components informations
 * about Libxml extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class PHP_CompatInfo_Reference_LibxmlTest
    extends PHP_CompatInfo_Reference_GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @covers PHP_CompatInfo_Reference_Libxml::getExtensions
     * @covers PHP_CompatInfo_Reference_Libxml::getFunctions
     * @covers PHP_CompatInfo_Reference_Libxml::getConstants
     * @covers PHP_CompatInfo_Reference_Libxml::getClasses
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$optionalconstants = array();
        if (defined('LIBXML_VERSION')) {
            if (LIBXML_VERSION < 20703) {
                self::$optionalconstants[] = 'LIBXML_PARSEHUGE';
            }
            if (LIBXML_VERSION < 20707) {
                self::$optionalconstants[] = 'LIBXML_HTML_NOIMPLIED';
            }
            if (LIBXML_VERSION < 20708) {
                self::$optionalconstants[] = 'LIBXML_HTML_NODEFDTD';
            }
        }

        if (DIRECTORY_SEPARATOR == '/') {

        } else {
            self::$optionalconstants = array(
                'LIBXML_HTML_NODEFDTD',
                'LIBXML_HTML_NOIMPLIED',
            );
        }

        self::$obj = new PHP_CompatInfo_Reference_Libxml();
        parent::setUpBeforeClass();
    }
}
