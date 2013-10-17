<?php
/**
 * Unit tests for PHP_CompatInfo package, Date Reference
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
 * about Date extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class PHP_CompatInfo_Reference_DateTest
    extends PHP_CompatInfo_Reference_GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @covers PHP_CompatInfo_Reference_Date::getExtensions
     * @covers PHP_CompatInfo_Reference_Date::getFunctions
     * @covers PHP_CompatInfo_Reference_Date::getConstants
     * @covers PHP_CompatInfo_Reference_Date::getClasses
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$obj = new PHP_CompatInfo_Reference_Date();
        parent::setUpBeforeClass();
    }
}
