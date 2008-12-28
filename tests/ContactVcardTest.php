<?php
// Copyright (c) 2007 Contaxis Limited

require_once 'PHPUnit/Framework/TestCase.php';

require_once "Contact_Vcard_Parse.php";

class ContactVcardParseTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        $familyName = "FamilyName";
        $givenName  = "GivenName";
        $additionalNames = "Additional Names";
        $prefix = "Prefix.";
        $suffix = "Suffix";
        $name = "A.N";

        $param1Name = "PARAM1";
        $param1Value = "PARAMVALUE1";
        $param2Name = "PARAM2";
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

    public function testPropertyGroups() {
        list($ret) = $this->parser->fromText($this->vcard);

        list($data) = $ret["A.N"];
        $values = $data['value'];

        $this->assertEquals("FamilyName", $values[0][0]);
        $this->assertEquals("GivenName", $values[1][0]);
        $this->assertEquals("Additional Names", $values[2][0]);
        $this->assertEquals("Prefix.", $values[3][0]);
        $this->assertEquals("Suffix", $values[4][0]);
    }

	public function testParameters() {
        list($ret) = $this->parser->fromText($this->vcard);

        list($data) = $ret["A.N"];


        $expected = array("PARAM1" => array("PARAMVALUE1"),
                          "PARAM2" => array("PARAMVALUE2"), 
                          'TYPE' => array("PARAMVALUE3"));

        $this->assertSame($expected, $data['param']);
    }
}

