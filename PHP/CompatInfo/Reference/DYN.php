<?php
/**
 * EXPERIMENTAL (all rules are not yet implemented)
 * Lazy loader for references
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CompatInfo
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  GIT: $Id$
 * @link     http://php5.laurent-laville.org/compatinfo/
 */

/**
 * Lazy loader for references
 *
 * @category PHP
 * @package  PHP_CompatInfo
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: @package_version@
 * @link     http://php5.laurent-laville.org/compatinfo/
 * @since    Class available since Release 2.23.0
 */
class PHP_CompatInfo_Reference_DYN
    extends PHP_CompatInfo_Reference_PHP4
{
    /**
     * Rules that should identify a reference to match for lazy loading
     */
    protected $rules;

    /**
     * Actual loaded references
     */
    protected $extensionReferences = array();

    /**
     * @param array $references (optional) Default references to prefetch
     * @param array $rules      (optional) Additional lazy loader rules
     * @param bool $prepend     Will add rules to head to stack rather than end by default
     */
    public function __construct(array $references = array(), array $rules = array(),
        $prepend = FALSE
    ) {
        $this->rules = array(
            array(
                'prefixes'  => array('Spl', 'spl_', 'class_', 'iterator_'),
                'suffixes'  => array('Iterator', 'Exception'),
                'contains'  => FALSE,
                'extension' => 'SPL',
            ),
            array(
                'prefixes'  => array('Mongo', 'bson_', 'MONGO'),
                'suffixes'  => FALSE,
                'contains'  => FALSE,
                'extension' => 'mongo',
            ),
            array(
                'prefixes'  => '*',
                'suffixes'  => FALSE,
                'contains'  => FALSE,
                'extension' => 'Core',
            ),
            array(
                'prefixes'  => '*',
                'suffixes'  => FALSE,
                'contains'  => FALSE,
                'extension' => 'standard',
            ),
        );
        if (count($rules) > 0) {
            if ($prepend === TRUE) {
                $this->rules = array_merge($rules, $this->rules);
            } else {
                $this->rules = array_merge($this->rules, $rules);
            }
        }

        if (count($references) > 0) {
            // references always loaded before start of parsing data sources
            foreach($references as $name) {
                $this->loadReference($name, TRUE);
            }
        }
    }

    /**
     * Returns a PHP_CompatInfo_Reference object.
     *
     * @param string $element  One of reference's element to discover
     * @param bool   $prefetch (optional) When FALSE, do a matching rules search.
     *                         Otherwise force a prefetch with $element
     *                         that identify a reference.
     *
     * @return mixed FALSE if reference could not be loaded or is not detected,
     *               reference object instance otherwise
     */
    public function loadReference($element, $prefetch = FALSE)
    {
        if ($prefetch === TRUE) {
            $extension = $element;
        } else {
            $extension = NULL;

            if ($this->match($element, $extension) === FALSE) {
                return FALSE;
            }
        }

        if (!array_key_exists($extension, $this->extensionReferences)) {

            $refClassName = 'PHP_CompatInfo_Reference_' . ucfirst(str_replace(' ', '', $extension));
            $refClassName = str_replace(' ', '_', $refClassName);
            if (class_exists($refClassName, true)) {
                $this->extensionReferences[$extension] = new $refClassName;
                //error_log('lazy load ref : '. $refClassName);
            } else {
                $this->warnings[] = "Could not load extension reference '$extension'";
                $this->extensionReferences[$extension] = FALSE;
            }
        }

        return $this->extensionReferences[$extension];
    }

    /**
     * Try to found the extension where Element is defined
     *

     * @param string $name       Element's name
     * @param string &$extension Element's extension found
     *
     * @return bool TRUE if found, FALSE otherwise
     */
    protected function match($name, &$extension)
    {
        $found = FALSE;

        foreach($this->rules as $rule) {
            if (isset($rule['prefixes']) && empty($rule['prefixes'])
                && isset($rule['suffixes']) && empty($rule['suffixes'])
                && isset($rule['contains']) && empty($rule['contains'])
            ) {
                continue;  // skip the empty rule
            }

            if (!$found && $rule['prefixes'] === '*') {
                // search into Core and standard references

                $functions = $this->extensionReferences[$rule['extension']]->getFunctions();
                if (array_key_exists($name, $functions)) {
                    $found = TRUE;
                    break 1;
                }
                unset($functions);

                $constants = $this->extensionReferences[$rule['extension']]->getConstants();
                if (array_key_exists($name, $constants)) {
                    $found = TRUE;
                    break 1;
                }
                unset($constants);

                $interfaces = $this->extensionReferences[$rule['extension']]->getInterfaces();
                if (array_key_exists($name, $interfaces)) {
                    $found = TRUE;
                    break 1;
                }
                unset($interfaces);

                $classes = $this->extensionReferences[$rule['extension']]->getClasses();
                if (array_key_exists($name, $classes)) {
                    $found = TRUE;
                    break 1;
                }
                unset($classes);

                if ('standard' === $rule['extension']) {
                    $tokens = $this->extensionReferences[$rule['extension']]->getTokens();
                    if (array_key_exists($name, $tokens)) {
                        $found = TRUE;
                        break 1;
                    }
                    unset($tokens);

                    $globals = $this->extensionReferences[$rule['extension']]->getGlobals();
                    if (array_key_exists($name, $globals)) {
                        $found = TRUE;
                        break 1;
                    }
                    unset($globals);
                }
            }

            if (!$found && !empty($rule['prefixes'])) {
                if (!is_array($rule['prefixes'])) {
                    $rule['prefixes'] = array( $rule['prefixes'] );
                }
                foreach( $rule['prefixes'] as $prefix ) {
                    if (strpos($name, $prefix) === 0) {
                        // the reference element name begin with this prefix
                        $found = TRUE;
                        break 2;
                    }
                }
            }

            if (!$found && !empty($rule['suffixes'])) {
                if (!is_array($rule['suffixes'])) {
                    $rule['suffixes'] = array( $rule['suffixes'] );
                }
                foreach( $rule['suffixes'] as $suffix ) {
                    if (substr($name, -1 * strlen($suffix)) === $suffix) {
                        // the reference element name end with this suffix
                        $found = TRUE;
                        break 2;
                    }
                }
            }

            if (!$found && !empty($rule['contains'])) {
                if (!is_array($rule['contains'])) {
                    $rule['contains'] = array( $rule['contains'] );
                }
                foreach( $rule['contains'] as $contains ) {
                    if (strpos($name, $contains) !== FALSE) {
                        // the reference element name contains this word
                        $found = TRUE;
                        break 2;
                    }
                }
            }
        }

        $extension = $found ? $rule['extension'] : NULL;
        return $found;
    }

    /**
     * Gets all informations at once about:
     * extensions, interfaces, classes, functions, constants, globals, tokens
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getAll($extension = null, $version = null, $condition = null)
    {
        $references = parent::getAll($extension, $version, $condition);
        return $references;
    }

    /**
     * Gets informations about extensions
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getExtensions($extension = null, $version = null, $condition = null)
    {
        $extensions = parent::getExtensions($extension, $version, $condition);
        return $extensions;
    }

    /**
     * Gets informations about interfaces
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getInterfaces($extension = null, $version = null, $condition = null)
    {
        $interfaces = parent::getInterfaces($extension, $version, $condition);
        return $interfaces;
    }

    /**
     * Gets informations about classes
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getClasses($extension = null, $version = null, $condition = null)
    {
        $classes = parent::getClasses($extension, $version, $condition);
        return $classes;
    }

    /**
     * Gets informations about functions
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getFunctions($extension = null, $version = null, $condition = null)
    {
        $functions = parent::getFunctions($extension, $version, $condition);
        return $functions;
    }

    /**
     * Gets informations about constants
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getConstants($extension = null, $version = null, $condition = null)
    {
        $constants = parent::getConstants($extension, $version, $condition);
        return $constants;
    }

    /**
     * Gets informations about superglobals
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getGlobals($extension = null, $version = null, $condition = null)
    {
        $globals = parent::getGlobals($extension, $version, $condition);
        return $globals;
    }

    /**
     * Gets informations about tokens (language features)
     *
     * @param string $extension (optional) NULL for PHP version,
     *                          TRUE if extension version
     * @param string $version   (optional) php or extension version
     * @param string $condition (optional) particular relationship with $version
     *                          Same operator values as used by version_compare
     *
     * @return array
     */
    public function getTokens($extension = null, $version = null, $condition = null)
    {
        $tokens = parent::getTokens($extension, $version, $condition);
        return $tokens;
    }

}
