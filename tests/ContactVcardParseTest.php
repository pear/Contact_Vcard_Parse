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
 * @todo      Make protected functions dataproviders.
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

    /**
     * @link http://pear.php.net/manual/en/package.fileformats.contact-vcard-parse.data.php
     *
     * @return string
     */
    protected function getExampleVcard()
    {
        $vcard  = "BEGIN:VCARD" . PHP_EOL;
        $vcard .= "VERSION:3.0" . PHP_EOL;
        $vcard .= "N:Shagnasty;Bolivar;Odysseus;Mr.;III,B.S." . PHP_EOL;
        $vcard .= "FN:Bolivar Shagnasty" . PHP_EOL;
        $vcard .= "ADR;TYPE=HOME,WORK:;;123 Main,Apartment 101;Beverly Hills;CA;90210" . PHP_EOL;
        $vcard .= "EMAIL;TYPE=HOME;TYPE=WORK:boshag@example.com" . PHP_EOL;
        $vcard .= "EMAIL;TYPE=PREF:boshag@ciaweb.net" . PHP_EOL;
        $vcard .= "END:VCARD";

        return $vcard;
    }

    /**
     * @link http://bugs.horde.org/view.php?actionID=view_file&type=vcf&file=SFMA.vcf&ticket=8366
     *
     * @return string
     */
    protected function getPropertyGroupVcard()
    {
        $vcard  = "BEGIN:VCARD" . "\n";
        $vcard .= "VERSION:3.0" . "\n";
        $vcard .= "N:Braunstein;Sharon;;;" . "\n";
        $vcard .= "FN:Sharon Braunstein" . "\n";
        $vcard .= "ORG:Seed & Feed\, Inc.;" . "\n";
        $vcard .= "EMAIL;type=INTERNET;type=WORK;type=pref:scrooge@seedandfeed.org" . "\n";
        $vcard .= "TEL;type=WORK;type=pref:404-688-6688" . "\n";
        $vcard .= "item1.ADR;type=HOME;type=pref:;;Attn\: Sharon Braunstein\nP.O. Box 5396;Atlanta;GA;31107;" . "\n";
        $vcard .= "item1.X-ABLabel:bill to" . "\n";
        $vcard .= "item1.X-ABADR:us" . "\n";
        $vcard .= "CATEGORIES:Customers:Verendus LLC" . "\n";
        $vcard .= "X-ABUID:8291364B-FCBF-4577-8294-166AC0E8B9C7\:ABPerson" . "\n";
        $vcard .= "END:VCARD";

        return $vcard;
    }

    /**
     * This test doesn't make any sense.
     */
    public function testPropertyGroups()
    {
        $this->markTestIncomplete("Property groups are not yet implemented and this test didn't make any sense!");
        return;

        $vcard = $this->getPropertyGroupVcard();

        list($ret) = $this->parser->fromText($vcard);

        list($data) = $ret["A.N"];
        $values = $data['value'];

        //var_dump($vcard, $values, $ret);

        $this->assertEquals("FamilyName", $values[0][0]);
        $this->assertEquals("GivenName", $values[1][0]);
        $this->assertEquals("Additional Names", $values[2][0]);
        $this->assertEquals("Prefix.", $values[3][0]);
        $this->assertEquals("Suffix", $values[4][0]);
    }

    /**
     * Test parameter parsing.
     *
     * @uses self::$parser
     * @uses self::$vcard
     */
    public function testParameters()
    {
        list($ret) = $this->parser->fromText($this->vcard);

        list($data) = $ret["A.N"];

        $expected = array("PARAM1" => array("PARAMVALUE1"),
                          "PARAM2" => array("PARAMVALUE2"), 
                          'TYPE' => array("PARAMVALUE3"));

        $this->assertSame($expected, $data['param']);
    }

    /**
     * This tests asserts that Contact_Vcard_Parse still behaves just like
     * the example online advertises.
     *
     * @return void
     * @uses   self::getExampleVcard()
     */
    public function testExampleParser()
    {
        $vcard  = $this->getExampleVcard();
        $parsed = $this->parser->fromText($vcard);

        //var_dump($parsed);
        $this->assertSame('3.0', $parsed[0]['VERSION'][0]['value'][0][0]);
        $this->assertSame('Shagnasty', $parsed[0]['N'][0]['value'][0][0]);
        $this->assertSame('Bolivar', $parsed[0]['N'][0]['value'][1][0]);
        $this->assertSame('Odysseus', $parsed[0]['N'][0]['value'][2][0]);
        $this->assertSame('Mr.', $parsed[0]['N'][0]['value'][3][0]);
        $this->assertSame('III', $parsed[0]['N'][0]['value'][4][0]);
        $this->assertSame('B.S.', $parsed[0]['N'][0]['value'][4][1]);
        $this->assertSame('Bolivar Shagnasty', $parsed[0]['FN'][0]['value'][0][0]);

        // Address
        $this->assertSame('HOME', $parsed[0]['ADR'][0]['param']['TYPE'][0]);
        $this->assertSame('WORK', $parsed[0]['ADR'][0]['param']['TYPE'][1]);
        $this->assertSame('123 Main', $parsed[0]['ADR'][0]['value'][2][0]);
        $this->assertSame('Apartment 101', $parsed[0]['ADR'][0]['value'][2][1]);
        $this->assertSame('Beverly Hills', $parsed[0]['ADR'][0]['value'][3][0]);
        $this->assertSame('CA', $parsed[0]['ADR'][0]['value'][4][0]);
        $this->assertSame('90210', $parsed[0]['ADR'][0]['value'][5][0]);
        $this->assertSame('', $parsed[0]['ADR'][0]['value'][6][0]);

        // Email
        $this->assertSame('HOME', $parsed[0]['EMAIL'][0]['param']['TYPE'][0]);
        $this->assertSame('WORK', $parsed[0]['EMAIL'][0]['param']['TYPE'][1]);
        $this->assertSame('boshag@example.com', $parsed[0]['EMAIL'][0]['value'][0][0]);
        $this->assertSame('PREF', $parsed[0]['EMAIL'][1]['param']['TYPE'][0]);
        $this->assertSame('boshag@ciaweb.net', $parsed[0]['EMAIL'][1]['value'][0][0]);
    }
}
