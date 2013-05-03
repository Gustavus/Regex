<?php

namespace Gustavus\Regex\Test;

use Gustavus\Regex\Regex;


/**
 * Test class for RegEx
 */
class RegexTest extends \PHPUnit_Framework_TestCase
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
   * @param string $functionName
   * @param string $testString
   * @param integer $expectedValue
   * @param array $expectedMatches
   */
  private function checkRegex($functionName, $testString, $expectedValue, array $expectedMatches = null)
  {
    $this->assertSame($expectedValue, preg_match(Regex::$functionName(), $testString, $matches));

    if (count($expectedMatches) > 0) {
      foreach ($expectedMatches as $position => $expectedMatch) {
        $this->assertSame($expectedMatch, $matches[$position]);
      }
    }
  }

  /**
   * @test
   */
  public function urlNotUrl()
  {
    $this->checkRegex('url', 'This is not a URL.', 0);
  }

  /**
   * @test
   */
  public function urlSimpleEdu()
  {
    $this->checkRegex('url', 'http://gustavus.edu', 1);
  }

  /**
   * @test
   */
  public function urlSimpleEduWithPadding()
  {
    $this->checkRegex('url', 'http://gustavus.edu ', 1);
  }

  /**
   * @test
   */
  public function urlSimpleEduInsideText()
  {
    $this->checkRegex('url', 'My website is at http://gustavus.edu now.', 1);
  }

  /**
   * @test
   */
  public function urlWithPort()
  {
    $this->checkRegex('url', 'http://gustavus.edu:1234', 1);
  }

  /**
   * @test
   */
  public function urlWithSecure()
  {
    $this->checkRegex('url', 'https://gustavus.edu ', 1);
  }

  /**
   * @test
   */
  public function urlWithSecureAndPort()
  {
    $this->checkRegex('url', 'https://gustavus.edu:1234 ', 1);
  }

  /**
   * @test
   */
  public function urlWithDirectory()
  {
    $this->checkRegex('url', 'http://gustavus.edu/about/', 1);
  }

  /**
   * @test
   */
  public function urlWithDirectoryAndFile()
  {
    $this->checkRegex('url', 'http://gustavus.eduabout/index.php', 1);
  }

  /**
   * @test
   */
  public function urlWithSecurePortDirectoryAndFile()
  {
    $this->checkRegex('url', 'https://gustavus.edu:1234/about/index.php', 1);
  }

  /**
   * @test
   */
  public function urlWithSecurePortDirectoryAndFileInsideText()
  {
    $this->checkRegex('url', 'The best website ever is https://gustavus.edu:1234/about/index.php now.', 1);
  }

  /**
   * @test
   */
  public function imgHtml()
  {
    $this->checkRegex('img', '<img src="myimage.jpg">', 1, array('<img src="myimage.jpg">', 'myimage.jpg'));
  }

  /**
   * @test
   */
  public function imgXml()
  {
    $this->checkRegex('img', '<img src="myimage.jpg"/>', 1, array('<img src="myimage.jpg"/>', 'myimage.jpg'));
  }

  /**
   * @test
   */
  public function emailAddressSimple()
  {
    $this->checkRegex('emailAddress', 'bvisto@gustavus.edu', 1, array('bvisto@gustavus.edu', 'bvisto', 'gustavus.edu'));
  }

  /**
   * @test
   */
  public function emailAddressWithTag()
  {
    $this->checkRegex('emailAddress', 'bvisto+test@gustavus.edu', 1, array('bvisto+test@gustavus.edu', 'bvisto+test', 'gustavus.edu'));
  }

  /**
   * @test
   */
  public function gustavusEmailAddressWithGustavus()
  {
    $this->checkRegex('gustavusEmailAddress', 'bvisto@gustavus.edu', 1, array('bvisto@gustavus.edu', 'bvisto'));
  }

  /**
   * @test
   */
  public function gustavusEmailAddressWithGac()
  {
    $this->checkRegex('gustavusEmailAddress', 'bvisto@gac.edu', 1, array('bvisto@gac.edu', 'bvisto'));
  }

  /**
   * @test
   */
  public function gustavusEmailAddressWithGustavusAndTag()
  {
    $this->checkRegex('gustavusEmailAddress', 'bvisto+test@gustavus.edu', 1, array('bvisto+test@gustavus.edu', 'bvisto+test'));
  }

  /**
   * @test
   */
  public function gustavusEmailAddressWithGacAndTag()
  {
    $this->checkRegex('gustavusEmailAddress', 'bvisto+test@gac.edu', 1, array('bvisto+test@gac.edu', 'bvisto+test'));
  }

  /**
   * @test
   */
  public function gustavusEmailAddressWithNonGustavusEmailAddress()
  {
    $this->checkRegex('gustavusEmailAddress', 'joe.lencioni@gmail.com', 0);
  }

  /**
   * @test
   */
  public function generatedEmailListMajors()
  {
    $this->checkRegex('generatedEmailList', 'rel-majors@lists.gustavus.edu', 1, array('rel-majors@lists.gustavus.edu', 'rel-majors'));
  }

  /**
   * @test
   */
  public function generatedEmailListMinors()
  {
    $this->checkRegex('generatedEmailList', 'rel-minors@lists.gustavus.edu', 1, array('rel-minors@lists.gustavus.edu', 'rel-minors'));
  }

  /**
   * @test
   */
  public function generatedEmailListMajorsSophomores()
  {
    $this->checkRegex('generatedEmailList', 'rel-majors-sophomore@lists.gustavus.edu', 1, array('rel-majors-sophomore@lists.gustavus.edu', 'rel-majors-sophomore'));
  }

  /**
   * @test
   */
  public function generatedEmailListMajorsJuniors()
  {
    $this->checkRegex('generatedEmailList', 'rel-majors-junior@lists.gustavus.edu', 1, array('rel-majors-junior@lists.gustavus.edu', 'rel-majors-junior'));
  }

  /**
   * @test
   */
  public function generatedEmailListMajorsSenior()
  {
    $this->checkRegex('generatedEmailList', 'rel-majors-senior@lists.gustavus.edu', 1, array('rel-majors-senior@lists.gustavus.edu', 'rel-majors-senior'));
  }

  /**
   * @test
   */
  public function generatedEmailListMinorsSophomores()
  {
    $this->checkRegex('generatedEmailList', 'rel-minors-sophomore@lists.gustavus.edu', 1, array('rel-minors-sophomore@lists.gustavus.edu', 'rel-minors-sophomore'));
  }

  /**
   * @test
   */
  public function generatedEmailListMinorsJuniors()
  {
    $this->checkRegex('generatedEmailList', 'rel-minors-junior@lists.gustavus.edu', 1, array('rel-minors-junior@lists.gustavus.edu', 'rel-minors-junior'));
  }

  /**
   * @test
   */
  public function generatedEmailListMinorsSenior()
  {
    $this->checkRegex('generatedEmailList', 'rel-minors-senior@lists.gustavus.edu', 1, array('rel-minors-senior@lists.gustavus.edu', 'rel-minors-senior'));
  }

  /**
   * @test
   */
  public function generatedEmailListFallCourseAlias()
  {
    $this->checkRegex('generatedEmailList', 'f-art-101-001@lists.gustavus.edu', 1, array('f-art-101-001@lists.gustavus.edu', 'f-art-101-001'));
  }

  /**
   * @test
   */
  public function generatedEmailListJanuaryCourseAlias()
  {
    $this->checkRegex('generatedEmailList', 'jt-art-101-001@lists.gustavus.edu', 1, array('jt-art-101-001@lists.gustavus.edu', 'jt-art-101-001'));
  }

  /**
   * @test
   */
  public function generatedEmailListSpringCourseAlias()
  {
    $this->checkRegex('generatedEmailList', 's-art-101-001@lists.gustavus.edu', 1, array('s-art-101-001@lists.gustavus.edu', 's-art-101-001'));
  }

  /**
   * @test
   */
  public function generatedEmailListSpringCourseAliasAllSections()
  {
    $this->checkRegex('generatedEmailList', 's-art-101-all@lists.gustavus.edu', 1, array('s-art-101-all@lists.gustavus.edu', 's-art-101-all'));
  }

  /**
   * @test
   */
  public function generatedEmailAdvisees()
  {
    $this->checkRegex('generatedEmailList', 'jcha-advisees@lists.gustavus.edu', 1, array('jcha-advisees@lists.gustavus.edu', 'jcha-advisees'));
  }

  /**
   * @test
   */
  public function generatedEmailListGustavusDomain()
  {
    $this->checkRegex('generatedEmailList', 'f-art-101-all@gustavus.edu', 1, array('f-art-101-all@gustavus.edu', 'f-art-101-all'));
  }

  /**
   * @test
   */
  public function generatedEmailListGacDomain()
  {
    $this->checkRegex('generatedEmailList', 'f-art-101-all@gac.edu', 1, array('f-art-101-all@gac.edu', 'f-art-101-all'));
  }

  /**
   * @test
   */
  public function generatedEmailListListsGacDomain()
  {
    $this->checkRegex('generatedEmailList', 'f-art-101-all@lists.gac.edu', 1, array('f-art-101-all@lists.gac.edu', 'f-art-101-all'));
  }

  /**
   * @test
   */
  public function generatedEmailListNonGeneratedEmailAddress()
  {
    $this->checkRegex('generatedEmailList', 'bvisto@gustavus.edu', 0);
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMajors()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-majors@lists.gustavus.edu', 1, array('rel-majors@lists.gustavus.edu', 'rel-majors'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMinors()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-minors@lists.gustavus.edu', 1, array('rel-minors@lists.gustavus.edu', 'rel-minors'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMajorsSophomores()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-majors-sophomore@lists.gustavus.edu', 1, array('rel-majors-sophomore@lists.gustavus.edu', 'rel-majors-sophomore'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMajorsJuniors()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-majors-junior@lists.gustavus.edu', 1, array('rel-majors-junior@lists.gustavus.edu', 'rel-majors-junior'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMajorsSenior()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-majors-senior@lists.gustavus.edu', 1, array('rel-majors-senior@lists.gustavus.edu', 'rel-majors-senior'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMinorsSophomores()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-minors-sophomore@lists.gustavus.edu', 1, array('rel-minors-sophomore@lists.gustavus.edu', 'rel-minors-sophomore'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMinorsJuniors()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-minors-junior@lists.gustavus.edu', 1, array('rel-minors-junior@lists.gustavus.edu', 'rel-minors-junior'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListMinorsSenior()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'rel-minors-senior@lists.gustavus.edu', 1, array('rel-minors-senior@lists.gustavus.edu', 'rel-minors-senior'));
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListNonGeneratedEmailAddress()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'bvisto@gustavus.edu', 0);
  }

  /**
   * @test
   */
  public function majorsOrMinorsEmailListGeneratedButNonMajorsOrMinorsEmailAddress()
  {
    $this->checkRegex('majorsOrMinorsEmailList', 'f-art-101-001@gustavus.edu', 0);
  }

  /**
   * @test
   */
  public function courseEmailListFallCourseAlias()
  {
    $this->checkRegex('courseEmailList', 'f-art-101-001@lists.gustavus.edu', 1, array('f-art-101-001@lists.gustavus.edu', 'f-art-101-001'));
  }

  /**
   * @test
   */
  public function courseEmailListJanuaryCourseAlias()
  {
    $this->checkRegex('courseEmailList', 'jt-art-101-001@lists.gustavus.edu', 1, array('jt-art-101-001@lists.gustavus.edu', 'jt-art-101-001'));
  }

  /**
   * @test
   */
  public function courseEmailListSpringCourseAlias()
  {
    $this->checkRegex('courseEmailList', 's-art-101-001@lists.gustavus.edu', 1, array('s-art-101-001@lists.gustavus.edu', 's-art-101-001'));
  }

  /**
   * @test
   */
  public function courseEmailListSpringCourseAliasAllSections()
  {
    $this->checkRegex('courseEmailList', 's-art-101-all@lists.gustavus.edu', 1, array('s-art-101-all@lists.gustavus.edu', 's-art-101-all'));
  }

  /**
   * @test
   */
  public function courseEmailListNonGeneratedEmailAddress()
  {
    $this->checkRegex('courseEmailList', 'bvisto@gustavus.edu', 0);
  }

  /**
   * @test
   */
  public function courseEmailListGeneratedButNonCourseEmailAddress()
  {
    $this->checkRegex('courseEmailList', 'rel-majors@gustavus.edu', 0);
  }

  /**
   * @test
   */
  public function adviseeEmailList()
  {
    $this->checkRegex('adviseeEmailList', 'jcha-advisees@lists.gustavus.edu', 1, array('jcha-advisees@lists.gustavus.edu', 'jcha-advisees'));
  }

  /**
   * @test
   */
  public function adviseeEmailListNonGeneratedEmailAddress()
  {
    $this->checkRegex('adviseeEmailList', 'bvisto@gustavus.edu', 0);
  }

  /**
   * @test
   */
  public function adviseeEmailListGeneratedButNonAdviseeEmailAddress()
  {
    $this->checkRegex('adviseeEmailList', 'rel-majors@gustavus.edu', 0);
  }

  /**
   * @return array
   */
  public static function houseNumberData()
  {
    return array(
      array('10141 313th Ave, Princeton, MN 55371', 1, array('10141')),
      array('9409 Hwy 1, City, ST 12345', 1, array('9409')),
      array('826 9th St. N, St. Peter, MN 56082', 1, array('826')),
      array('120 1/2 Louisiana Ave, City, ST 12345', 1, array('120 1/2')),
      array('1234 9 mile road, City, ST 12345', 1, array('1234')),
      array('1234 1/2 St, City, ST 12345', 1, array('1234')),
      array('1234-1/2 Hwy 1, City, ST 12345', 1, array('1234-1/2')),
    );
  }

  /**
   * @test
   * @dataProvider houseNumberData
   * @param string $testString
   * @param string $expectedValue
   * @param array $matches
   */
  public function houseNumber($testString, $expectedValue, $matches)
  {
    $this->checkRegex('houseNumber', $testString, $expectedValue, $matches);
  }

  /**
   * @test
   * @dataProvider dataForURIRegex
   */
  public function testURIRegex($value, $expected)
  {
    $this->checkRegex('uri', $value, $expected);
  }

  /**
   * Data provider for the testURIRegex test.
   *
   * @return array
   */
  public function dataForURIRegex()
  {
    return [
      ['http://stackoverflow.com/questions/5496656/check-if-item-can-be-converted-to-string', 1],
      ['http://tools.ietf.org/html/rfc3986#section-3', 1],
      ['http://63.197.151.31/blog/machine-learning-and-link-spam-my-brush-with-insanity', 1],
      ['http://www.smashingapps.com/2009/01/13/11-great-hidden-things-google-can-do-that-you-should-know.html', 1],
      ['http://[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80/index.html', 1],
      ['http://hq.squidoo.com/squid-news/make-a-lens-win-a-monkey-well-sort-of/', 1],
      ['http://[fec0::abcd%251]/', 1],
      ['http://tinyurl.com/', 1],
      ['http://www.squidoo.com/how-to-write-and-self-publish-your-first-book', 1],
      ['https://www.google.com/search?q=restaurants&tbm=plcs', 1],

      ['blarghbaduri', 0],
      ['www.noscheme.com/is/no/good', 0]
    ];
  }

  /**
   * @test
   * @dataProvider dataForURIRegex
   */
  public function testPhoneURIRegex($value, $expected)
  {
    $this->checkRegex('uri', $value, $expected);
  }

  /**
   * Data provider for the testPhoneURIRegex test.
   *
   * @return array
   */
  public function dataForPhoneURIRegex()
  {
    return [
      ['867-5309', 1],
      ['tel:+1-555-438-3732', 1],
      ['tel:+1-555-438-3732;ext=1234', 1],
      ['tel:7042;phone-context=example.com', 1],
      ['tel:1234;phone-context=munich.example.com', 1],
      ['tel:863-1234;phone-context=+1-914-555', 1],

      ['blarghbadphonenumber', 0]
    ];
  }
}
