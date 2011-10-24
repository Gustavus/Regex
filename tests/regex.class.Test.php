<?php

require_once '/cis/lib/regex/regex.class.php';

/**
 * Test class for RegEx.
 * Generated by PHPUnit on 2011-10-24 at 15:58:53.
 */
class RegExTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @return void
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     * @return void
     */
    protected function tearDown()
    {
    }

    /**
     * @test
     */
    public function urlNotUrl()
    {
      $this->assertSame(0, preg_match(RegEx::url(), 'This is not a URL.'));
    }

    /**
     * @test
     */
    public function urlSimpleEdu()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'http://gustavus.edu'));
    }

    /**
     * @test
     */
    public function urlSimpleEduWithPadding()
    {
      $this->assertSame(1, preg_match(RegEx::url(), ' http://gustavus.edu '));
    }

    /**
     * @test
     */
    public function urlSimpleEduInsideText()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'My website is at http://gustavus.edu now.'));
    }

    /**
     * @test
     */
    public function urlWithPort()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'http://gustavus.edu:1234'));
    }

    /**
     * @test
     */
    public function urlWithSecure()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'https://gustavus.edu'));
    }

    /**
     * @test
     */
    public function urlWithSecureAndPort()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'https://gustavus.edu:1234'));
    }

    /**
     * @test
     */
    public function urlWithDirectory()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'http://gustavus.edu/about/'));
    }

    /**
     * @test
     */
    public function urlWithDirectoryAndFile()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'http://gustavus.edu/about/index.php'));
    }

    /**
     * @test
     */
    public function urlWithSecurePortDirectoryAndFile()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'https://gustavus.edu:1234/about/index.php'));
    }

    /**
     * @test
     */
    public function urlWithSecurePortDirectoryAndFileInsideText()
    {
      $this->assertSame(1, preg_match(RegEx::url(), 'The best website ever is https://gustavus.edu:1234/about/index.php now.'));
    }

    /**
     * @test
     */
    public function imgHtml()
    {
      $this->assertSame(1, preg_match(RegEx::img(), '<img src="myimage.jpg">', $matches));
      $this->assertSame('<img src="myimage.jpg">', $matches[0]);
      $this->assertSame('myimage.jpg', $matches[1]);
    }

    /**
     * @test
     */
    public function imgXml()
    {
      $this->assertSame(1, preg_match(RegEx::img(), '<img src="myimage.jpg"/>', $matches));
      $this->assertSame('<img src="myimage.jpg"/>', $matches[0]);
      $this->assertSame('myimage.jpg', $matches[1]);
    }

    /**
     * @test
     */
    public function emailAddressSimple()
    {
      $this->assertSame(1, preg_match(RegEx::emailAddress(), 'jlencion@gustavus.edu', $matches));
      $this->assertSame('jlencion@gustavus.edu', $matches[0]);
      $this->assertSame('jlencion', $matches[1]);
      $this->assertSame('gustavus.edu', $matches[2]);
    }

    /**
     * @test
     */
    public function emailAddressWithTag()
    {
      $this->assertSame(1, preg_match(RegEx::emailAddress(), 'jlencion+test@gustavus.edu', $matches));
      $this->assertSame('jlencion+test@gustavus.edu', $matches[0]);
      $this->assertSame('jlencion+test', $matches[1]);
      $this->assertSame('gustavus.edu', $matches[2]);
    }

    /**
     * @test
     */
    public function gustavusEmailAddressWithGustavus()
    {
      $this->assertSame(1, preg_match(RegEx::gustavusEmailAddress(), 'jlencion@gustavus.edu', $matches));
      $this->assertSame('jlencion@gustavus.edu', $matches[0]);
      $this->assertSame('jlencion', $matches[1]);
    }

    /**
     * @test
     */
    public function gustavusEmailAddressWithGac()
    {
      $this->assertSame(1, preg_match(RegEx::gustavusEmailAddress(), 'jlencion@gac.edu', $matches));
      $this->assertSame('jlencion@gac.edu', $matches[0]);
      $this->assertSame('jlencion', $matches[1]);
    }

    /**
     * @test
     */
    public function gustavusEmailAddressWithGustavusAndTag()
    {
      $this->assertSame(1, preg_match(RegEx::gustavusEmailAddress(), 'jlencion+test@gustavus.edu', $matches));
      $this->assertSame('jlencion+test@gustavus.edu', $matches[0]);
      $this->assertSame('jlencion+test', $matches[1]);
    }

    /**
     * @test
     */
    public function gustavusEmailAddressWithGacAndTag()
    {
      $this->assertSame(1, preg_match(RegEx::gustavusEmailAddress(), 'jlencion+test@gac.edu', $matches));
      $this->assertSame('jlencion+test@gac.edu', $matches[0]);
      $this->assertSame('jlencion+test', $matches[1]);
    }

    /**
     * @test
     */
    public function gustavusEmailAddressWithNonGustavusEmailAddress()
    {
      $this->assertSame(0, preg_match(RegEx::gustavusEmailAddress(), 'joe.lencioni@gmail.com', $matches));
    }

}
