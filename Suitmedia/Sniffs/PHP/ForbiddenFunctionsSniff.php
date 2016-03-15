<?php
/**
 * PHP Version 5
 *
 * @package       php-code-standards
 * @copyright     Copyright (c) PT Suitmedia Kreasi Indonesia. (https://suitmedia.com)
 * @license       https://github.com/suitmedia/php-code-standards/blob/master/LICENSE.txt MIT License
 * @link          https://packagist.org/packages/suitmedia/php-code-standards
 */

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * Suitmedia_Sniffs_PHP_ForbiddenFunctionsSniff.
 *
 * Discourages the use of any functions that will break the code security and stability.
 */
class Suitmedia_Sniffs_PHP_ForbiddenFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is NULL if no alternative exists. IE, the
     * function should just not be used.
     *
     * @var array(string => string|null)
     */
    public $forbiddenFunctions = [
        'die' => null,
        'exit' => null,
        'dd' => null,
        'phpinfo' => null,
        'eval' => null,
        'assert' => null,
    ];
}
