<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Contact_Vcard_Parse_AllTests::main');
}

if ($fp = @fopen('PHPUnit/Autoload.php', 'r', true)) {
    require_once 'PHPUnit/Autoload.php';
} elseif ($fp = @fopen('PHPUnit/Framework.php', 'r', true)) {
    require_once 'PHPUnit/Framework.php';
    require_once 'PHPUnit/TextUI/TestRunner.php';
} else {
    die('skip could not find PHPUnit');
}
fclose($fp);

// Uncomment if this is run from a SVN checkout
//$inSvnDir = realpath(dirname(__FILE__) . '/../');
//set_include_path($inSvnDir . PATH_SEPARATOR . get_include_path());
//
// Or, better yet, run the test suite from the directory above, like this:
// php -d error_reporting=22527 tests/AllTests.php

require_once dirname(__FILE__) . '/ContactVcardParseTest.php';

class Contact_Vcard_Parse_AllTests {

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite( "ContactVcardParse Tests");
        $suite->addTestSuite('ContactVcardParseTest');
        return $suite;
    }

}

if (PHPUnit_MAIN_METHOD == 'Contact_Vcard_Parse_AllTests::main') {
    Contact_Vcard_Parse_AllTests::main();
}

