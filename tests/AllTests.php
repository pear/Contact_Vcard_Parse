<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Contact_Vcard_Parse_AllTests::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Framework/TestSuite.php';

// Uncomment if this is run from a SVN checkout
//$inSvnDir = realpath(dirname(__FILE__) . '/../');
//set_include_path($inSvnDir . PATH_SEPARATOR . get_include_path());

require_once 'ContactVcardParseTest.php';

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

