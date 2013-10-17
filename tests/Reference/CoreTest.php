<?php
/**
 * Unit tests for PHP_CompatInfo package, Core Reference
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
 * about Core
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class PHP_CompatInfo_Reference_CoreTest
    extends PHP_CompatInfo_Reference_GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @covers PHP_CompatInfo_Reference_Core::getExtensions
     * @covers PHP_CompatInfo_Reference_Core::getFunctions
     * @covers PHP_CompatInfo_Reference_Core::getClasses
     * @covers PHP_CompatInfo_Reference_Core::getInterfaces
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$optionalconstants = array(
            // Not real constant
            '__CLASS__',
            '__FILE__',
            '__FUNCTION__',
            '__LINE__',
            '__COMPILER_HALT_OFFSET__',
            '__DIR__',
            '__METHOD__',
            '__NAMESPACE__',
            '__TRAIT__',
        );
        self::$ignoredconstants = array(
            // add by swig framework as core constant
            'swig_runtime_data_type_pointer',
        );
        self::$ignoredfunctions = array(
            // Provided by PHP/CodeCoverage/Util.php when not available in PHP
            // So no reliable check for this one
            'trait_exists',
        );
        if (DIRECTORY_SEPARATOR == '/') {
            self::$optionalconstants = array_merge(
                self::$optionalconstants,
                array(
                    // Win32 Only
                    'PHP_WINDOWS_VERSION_MAJOR',
                    'PHP_WINDOWS_VERSION_MINOR',
                    'PHP_WINDOWS_VERSION_BUILD',
                    'PHP_WINDOWS_VERSION_PLATFORM',
                    'PHP_WINDOWS_VERSION_SP_MAJOR',
                    'PHP_WINDOWS_VERSION_SP_MINOR',
                    'PHP_WINDOWS_VERSION_SUITEMASK',
                    'PHP_WINDOWS_VERSION_PRODUCTTYPE',
                    'PHP_WINDOWS_NT_DOMAIN_CONTROLLER',
                    'PHP_WINDOWS_NT_SERVER',
                    'PHP_WINDOWS_NT_WORKSTATION',
                )
            );
        } else {
            self::$optionalconstants = array_merge(
                self::$optionalconstants,
                array(
                    // Non Windows only
                    'PHP_MANDIR',
                )
            );
        }
        if (php_sapi_name() != 'cli') {
            array_push(self::$optionalconstants, 'STDIN', 'STDOUT', 'STDERR');
        }

        self::$optionalfunctions = array(
            // Requires ZTS
            'zend_thread_id',
        );
        self::$obj = new PHP_CompatInfo_Reference_Core();
        self::$ref = self::$obj->getAll();

        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            // this is a hack...
            self::$ref['extensions']['internal'] = self::$ref['extensions']['Core'];
            unset(self::$ref['extensions']['Core']);
        }
    }

    /**
     * Test that each functions are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromExtension()
    {
        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            $this->markTestSkipped(
                "Can't be tested in php " . PHP_VERSION
            );
        } else {
            parent::testGetFunctionsFromExtension();
        }
    }
}
