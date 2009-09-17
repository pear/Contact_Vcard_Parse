<?php
/**
 * Parse vCard 2.1 and 3.0 text blocks.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 2.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/2_02.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the world-wide-web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  File_Formats
 * @package   Contact_Vcard_Parse
 * @author    Till Klampaeckel <till@php.net>
 * @copyright Copyright (c) 2007 Contaxis Limited
 * @license   http://www.php.net/license/2_02.txt  PHP License 2.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Contact_Vcard_Parse
 */

/**
 * PHPUnit_Framework_TestCase
 * @ignore
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Contact_Vcard_Parse
 */
require_once "Contact/Vcard/Parse.php";

/**
 * Tests for Contact_Vcard_Parse.
 *
 * @category  File_Formats
 * @package   Contact_Vcard_Parse
 * @author    Till Klampaeckel <till@php.net>
 * @copyright Copyright (c) 2007 Contaxis Limited
 * @license   http://www.php.net/license/2_02.txt  PHP License 2.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Contact_Vcard_Parse
 */
class ContactVcardParseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     * @see self::setUp()
     */
    protected $vcard;

    /**
     * @var Contact_Vcard_Parse
     * @see self::setUp()
     */
    protected $parser;

    /**
     * setUp()
     *
     * Setup a vcard (in {@link self::$vcard}.
     *
     * @return void
     * @uses   self::$vcard
     * @uses   self::$parser
     */
    public function setUp()
    {
        $familyName      = "FamilyName";
        $givenName       = "GivenName";
        $additionalNames = "Additional Names";
        $prefix          = "Prefix.";
        $suffix          = "Suffix";
        $name            = "A.N";

        $param1Name  = "PARAM1";
        $param1Value = "PARAMVALUE1";
        $param2Name  = "PARAM2";
        $param2Value = "PARAMVALUE2";
        $param3Value = "PARAMVALUE3";

        $this->vcard = "BEGIN:VCARD\n\r" .
            $name .
            ";" . $param1Name . "=" . $param1Value .
            ";" . $param2Name . "=" . $param2Value .
            ";" . $param3Value .
            ":" .
            $familyName . ";" .
            $givenName . ";" .
            $additionalNames . ";" .
            $prefix . ";" .
            $suffix . "\n\r" .
            "END:VCARD";

        $this->parser = new Contact_Vcard_Parse();
    }

    public function testPropertyGroups()
    {
        list($ret) = $this->parser->fromText($this->vcard);

        list($data) = $ret["A.N"];
        $values = $data['value'];

        $this->assertEquals("FamilyName", $values[0][0]);
        $this->assertEquals("GivenName", $values[1][0]);
        $this->assertEquals("Additional Names", $values[2][0]);
        $this->assertEquals("Prefix.", $values[3][0]);
        $this->assertEquals("Suffix", $values[4][0]);
    }

	public function testParameters()
    {
        list($ret) = $this->parser->fromText($this->vcard);

        list($data) = $ret["A.N"];

        $expected = array("PARAM1" => array("PARAMVALUE1"),
                          "PARAM2" => array("PARAMVALUE2"), 
                          'TYPE' => array("PARAMVALUE3"));

        $this->assertSame($expected, $data['param']);
    }
}
