<?php
/**
 * Unit tests for PHP_CompatInfo package, Json Reference
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
 * about Json extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class PHP_CompatInfo_Reference_JsonTest
    extends PHP_CompatInfo_Reference_GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @covers PHP_CompatInfo_Reference_Json::getExtensions
     * @covers PHP_CompatInfo_Reference_Json::getFunctions
     * @covers PHP_CompatInfo_Reference_Json::getConstants
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // New features of JSONC alternative extension
        self::$ignoredconstants = array(
            'JSON_C_BUNDLED',
            'JSON_C_VERSION',
            'JSON_PARSER_NOTSTRICT',
        );
        self::$ignoredclasses = array(
            'JsonIncrementalParser',
        );
        self::$obj = new PHP_CompatInfo_Reference_Json();
        parent::setUpBeforeClass();
    }
}
