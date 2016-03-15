<?php
/**
 * PHP Version 5
 *
 * @package       php-code-standards
 * @copyright     Copyright (c) PT Suitmedia Kreasi Indonesia. (https://suitmedia.com)
 * @license       https://github.com/suitmedia/php-code-standards/blob/master/LICENSE.txt MIT License
 * @link          https://packagist.org/packages/suitmedia/php-code-standards
 */
namespace SuitmediaCS\Tests;

/**
 * Abstract class which contain set of logics
 * to help you perform unit testing of any sniffs.
 */
abstract class AbstractSniffUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Current test class name
     *
     * @var string
     */
    protected static $classFilename = null;

    /**
     * The PHP_CodeSniffer object used for testing.
     *
     * @var PHP_CodeSniffer
     */
    protected static $phpcs = null;

    /**
     * The sniff code to be test
     *
     * @var string
     */
    protected static $sniffCode = null;

    /**
     * Our code standard name
     *
     * @var string
     */
    protected static $standardName = 'Suitmedia';

    /**
     * the additional test files, to be used in this test
     *
     * @var array
     */
    protected static $testFiles = [];

    /**
     * Sets up this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        if (self::$phpcs === null) {
            self::$phpcs = new \PHP_CodeSniffer();
        }

        $reflector = new \ReflectionClass(get_class($this));

        self::$classFilename = $reflector->getFileName();
        self::getTestFiles();

        $parts = explode('\\', get_class($this));
        self::$sniffCode = self::$standardName . '.' . $parts[3] . '.' . preg_replace('/UnitTest/', '', $parts[4]);
    }

    /**
     * Populate all related test files
     *
     * @return void
     */
    private static function getTestFiles()
    {
        $testPattern = dirname(self::$classFilename) . DIRECTORY_SEPARATOR . basename(self::$classFilename, '.php');
        $dir = new \DirectoryIterator(dirname(self::$classFilename));
        foreach ($dir as $file) {
            $path = $file->getPathName();
            
            if ((strpos($path, $testPattern) === 0) && (substr($path, -4) == '.inc')) {
                self::$testFiles[] = $path;
            }
        }
    }

    /**
     * Should this test be skipped for some reason.
     *
     * @return void
     */
    protected function shouldSkipTest()
    {
        return false;
    }

    /**
     * Tests the extending classes Sniff class.
     *
     * @return void
     * @throws PHPUnit_Framework_Error
     */
    final public function testSniff()
    {
        // Skip this test if we can't run in this environment.
        if ($this->shouldSkipTest() === true) {
            $this->markTestSkipped();
        }

        self::$phpcs->initStandard(self::$standardName, [self::$sniffCode]);
        self::$phpcs->setIgnorePatterns([]);

        $failureMessages = [];

        foreach (self::$testFiles as $testFile) {
            $filename = basename($testFile);

            try {
                $cliValues = $this->getCliValues($filename);
                self::$phpcs->cli->setCommandLineValues($cliValues);
                $phpcsFile = self::$phpcs->processFile($testFile);
            } catch (Exception $e) {
                $this->fail('An unexpected exception has been caught: '.$e->getMessage());
            }

            $failures = $this->generateFailureMessages($phpcsFile);
            $failureMessages = array_merge($failureMessages, $failures);
        }

        if (empty($failureMessages) === false) {
            $this->fail(implode(PHP_EOL, $failureMessages));
        }
    }

    /**
     * Generate a list of test failures for a given sniffed file.
     *
     * @param PHP_CodeSniffer_File $file The file being tested.
     *
     * @return array
     * @throws PHP_CodeSniffer_Exception
     */
    public function generateFailureMessages(\PHP_CodeSniffer_File $file)
    {
        $testFile = $file->getFilename();

        $foundErrors      = $file->getErrors();
        $foundWarnings    = $file->getWarnings();
        $expectedErrors   = $this->getErrorList(basename($testFile));
        $expectedWarnings = $this->getWarningList(basename($testFile));

        if (is_array($expectedErrors) === false) {
            throw new \PHP_CodeSniffer_Exception('getErrorList() must return an array');
        }

        if (is_array($expectedWarnings) === false) {
            throw new \PHP_CodeSniffer_Exception('getWarningList() must return an array');
        }

        $failureMessages = [];

        $failureMessages = array_merge($failureMessages, self::validateFailures(
            $foundErrors,
            $expectedErrors,
            basename($testFile),
            'error'
        ));

        $failureMessages = array_merge($failureMessages, self::validateFailures(
            $foundWarnings,
            $expectedWarnings,
            basename($testFile),
            'warning'
        ));

        return $failureMessages;
    }

    /**
     * Validate if the occurances that has been found matches perfectly with
     * the given expectations.
     *
     * @param  array $occurances
     * @param  array $expectations
     * @param  string $filename
     * @param  string $failureClass
     * @return array
     */
    protected static function validateFailures($occurances, $expectations, $filename, $failureClass = 'error')
    {
        $messages = [];

        foreach ($occurances as $line => $occurance) {
            foreach ($occurance as $column => $failures) {
                $failureCount = count($failures);
                if (!isset($expectations[$line]) || !$expectations[$line]) {
                    $currentMessage = '[LINE ' . $line . '] Expected 0 '. $failureClass .' in ' . $filename;
                    $currentMessage .= ' but found ' . $failureCount . ' '. $failureClass .'(s) ';
                    $currentMessage .= 'at column '. $column . '.';
                    $messages[] =  $currentMessage;
                }
            }
        }

        foreach ($expectations as $line => $expectation) {
            $realOccurances = (isset($occurances[$line])) ? count($occurances[$line]) : 0;

            if ($realOccurances != $expectation) {
                $currentMessage = '[LINE ' . $line . '] Expected '. $expectation .' '. $failureClass .'(s)';
                $currentMessage .= ' in ' . $filename;
                $currentMessage .= ' but found ' . $realOccurances . ' '. $failureClass .'(s).';
                $messages[] =  $currentMessage;
            }
        }

        return $messages;
    }

    /**
     * Get a list of CLI values to set before the file is tested.
     *
     * @param string $filename The name of the file being tested.
     *
     * @return array
     */
    public function getCliValues($filename = null)
    {
        return ['-s'];
    }

    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    abstract protected function getErrorList($filename = null);

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    abstract protected function getWarningList($filename = null);
}
