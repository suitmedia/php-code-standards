<?php
/**
 * PHP Version 5
 *
 * @package       php-code-standards
 * @copyright     Copyright (c) PT Suitmedia Kreasi Indonesia. (https://suitmedia.com)
 * @license       https://github.com/suitmedia/php-code-standards/blob/master/LICENSE.txt MIT License
 * @link          https://packagist.org/packages/suitmedia/php-code-standards
 */
namespace SuitmediaCS\Tests\Sniffs\PHP;

use SuitmediaCS\Tests\AbstractSniffUnitTest;

/**
 * Unit Testing for ForbiddenFunctionsSniff
 */
class ForbiddenFunctionsUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getErrorList($filename = null)
    {
        return [
            9 => 1,
            10 => 1,
            11 => 1,
            12 => 1,
            13 => 1,
            14 => 1,
            15 => 1,
            16 => 1,
        ];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList($filename = null)
    {
        return [];
    }
}
