<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'ContactVcardParse_AllTests::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'ContactVcardParseTest.php';

class ContactVcardParse_AllTests {

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite( "ContactVcardParse Tests");
        $suite->addTestSuite('ContactVcardParseTest');
        return $suite;
    }

}

if (PHPUnit_MAIN_METHOD == 'ContactVcardParse_AllTests::main') {
    ContactVcardParse_AllTests::main();
}

