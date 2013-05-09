<?php
namespace Gustavus\Regex;

class Regex
{

  /**
   * @return string
   * @todo adjust for internationalized domain names
   */
  final public static function url()
  {
    return '{
      \\b
      # Match the leading part (proto://hostname, or just hostname)
      (
        # http://, or https:// leading part
        (https?)://[-\\w]+(\\.\\w[-\\w]*)+
      |
        # or, try to find a hostname with more specific sub-expression
        (?i: [a-z0-9] (?:[-a-z0-9]*[a-z0-9])? \\. )+ # sub domains
        # Now ending .com, etc. For these, require lowercase
        (?-i: com\\b
            | edu\\b
            | biz\\b
            | gov\\b
            | in(?:t|fo)\\b # .int or .info
            | mil\\b
            | net\\b
            | org\\b
            | [a-z][a-z]\\.[a-z][a-z]\\b # two-letter country code
        )
      )

      # Allow an optional port number
      ( : \\d+ )?

      # The rest of the URL is optional, and begins with /
      (
        /
        # The rest are heuristics for what seems to work well
        [^.!,?;"\\\'<>()\[\]\{\}\s\x7F-\\xFF]*
        (
          [.!,?]+ [^.!,?;"\\\'<>()\\[\\]\{\\}\s\\x7F-\\xFF]+
        )*
      )?
    }ix';
  }

  /**
   * [0] => entire img tag (e.g. <img src="example.jpg" alt="example"/>)
   * [1] => value of src attribute (e.g. example.jpg)
   *
   * @return string
   */
  final public static function img()
  {
    return '{<\s*img[^>]*src\s*=\s*[\'"]\s*([^\'"]+)\s*[\'"][^>]*>}i';
  }

  /**
   * [0] => entire email address (user@domain.com)
   * [1] => user (everything before the @)
   * [2] => domain (everything after the @)
   *
   * @return string
   * @todo adjust for internationalized domain names
   */
  final public static function emailAddress()
  {
    return '{^([0-9a-zA-Z](?:[-.\w]*[0-9a-zA-Z_+])*)@((?:[0-9a-zA-Z][-\w]*\.)+[a-zA-Z]{2,9})$}';
  }

  /**
   * [0] => entire email address
   * [1] => user (everything before the @)
   *
   * @return string
   */
  final public static function gustavusEmailAddress()
  {
    return '{(.+)@(?:.+\.)?(?:gustavus|gac)\.edu}i';
  }

  /**
   * [0] => entire email address
   * [1] => user (everything before the @)
   *
   * @return string
   */
  final public static function generatedEmailList()
  {
    return '{
      ^
      (
        # Match majors and minors (abcd-majors, abcd-majors-senior, etc.)
        [a-z]+-m(?:aj|in)ors(?:-(?:senior|junior|sophomore))?

        |

        # Match course aliases (f-ART-100-001)
        (?:f|jt|s)-[A-Z/]{3}-\d{3}-(?:\d{3}|all)

        |

        # Match advisees
        \w+-advisees
      )

      # Match @gustavus.edu, @gac.edu, @lists.gustavus.edu, or @lists.gac.edu
      @(?:lists\.)?(?:gustavus|gac)\.edu
      $
    }xi';
  }

  /**
   * [0] => entire email address
   * [1] => user (everything before the @)
   *
   * @return string
   */
  final public static function majorsOrMinorsEmailList()
  {
    return '{
      ^
      (
        # Match majors and minors (abcd-majors, abcd-majors-senior, etc.)
        [a-z]+-m(?:aj|in)ors(?:-(?:senior|junior|sophomore))?
      )

      # Match @gustavus.edu, @gac.edu, @lists.gustavus.edu, or @lists.gac.edu
      @(?:lists\.)?(?:gustavus|gac)\.edu
      $
    }xi';
  }

  /**
   * [0] => entire email address
   * [1] => user (everything before the @)
   *
   * @return string
   */
  final public static function courseEmailList()
  {
    return '{
      ^
      (
        # Match course aliases (f-ART-100-001)
        (?:f|jt|s)-[A-Z/]{3}-\d{3}-(?:\d{3}|all)
      )

      # Match @gustavus.edu, @gac.edu, @lists.gustavus.edu, or @lists.gac.edu
      @(?:lists\.)?(?:gustavus|gac)\.edu
      $
    }xi';
  }

  /**
   * [0] => entire email address
   * [1] => user (everything before the @)
   * [2] => advisor username (e.g. fsmash)
   *
   * @return string
   */
  final public static function adviseeEmailList()
  {
    return '{
      ^
      (
        # Match advisees
        (\w+)-advisees
      )

      # Match @gustavus.edu, @gac.edu, @lists.gustavus.edu, or @lists.gac.edu
      @(?:lists\.)?(?:gustavus|gac)\.edu
      $
    }xi';
  }

  /**
   * [0] => house number
   *
   * @return string
   */
  final public static function houseNumber()
  {
    return '{
      ^
      (?:
        # Match any set of consecutive digits
        \d+

        (?:
          # Optionally followed by spaces or hyphens and a fraction
          [\s\-]+\d/\d\b

          # That cannot be followed by a set of letters and a comma or period
          (?!\s+[a-zA-Z]+[,\.])
        )?
      )
    }x';
  }


  /**
   * Builds a regular expression that can be used to validate a URI according to RFC3986 and
   * RFC6874.
   *
   * The capture groups for the expression are as follows:
   * <ul>
   *  <li>0: complete uri</li>
   *  <li>1: scheme</li>
   *  <li>2: hierarchical components</li>
   *  <li>3: query</li>
   *  <li>4: fragment</li>
   * </ul>
   *
   * Note:
   *  This function uses a fair amount of string concatenation to build the expression. The
   *  expression returned by this function should be stored and reused as necessary instead of
   *  making multiple calls to this function.
   *
   * @return string
   *  A regular expression capable of validating URIs.
   */
  public static function uri()
  {
    //   foo://example.com:8042/over/there?name=ferret#nose
    //   \_/   \______________/\_________/ \_________/ \__/
    //    |           |            |            |        |
    // scheme     authority       path        query   fragment
    //    |   _____________________|__
    //   / \ /                        \
    //   urn:example:animal:ferret:nose

    $pctencoded   = '%[A-Fa-f0-9]{2}';
    $gendelims    = '[:/?#\\[\\]@]';
    $subdelims    = '[!$&\'()*+,;=]';
    $reserved     = "(?:{$gendelims}|{$subdelims})";
    $unreserved   = '[A-Za-z0-9.\\-_~]';
    $scheme       = '[A-Za-z][A-Za-z0-9+.\\-]*';
    $userinfo     = "(?:{$unreserved}|{$pctencoded}|{$subdelims}|:)*";

    $decoctet     = '(?:[0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])';
    $ipv4address  = "{$decoctet}\\.{$decoctet}\\.{$decoctet}\\.{$decoctet}";

    $h16          = '[A-Fa-f0-9]{1,4}';
    $ls32         = "(?:{$h16}:{$h16}|{$ipv4address})";
    $ipv6address  = "(?:(?:{$h16}:){6}{$ls32}|::(?:{$h16}:){5}{$ls32}|{$h16}?::(?:{$h16}:){4}{$ls32}|(?:(?:{$h16}:){0,1}{$h16})?::(?:{$h16}:){3}{$ls32}|(?:(?:{$h16}:){0,2}{$h16})?::(?:{$h16}:){2}{$ls32}|(?:(?:{$h16}:){0,3}{$h16})?::{$h16}:{$ls32}|(?:(?:{$h16}:){0,4}{$h16})?::{$ls32}|(?:(?:{$h16}:){0,5}{$h16})?::{$h16}|(?:(?:{$h16}:){0,6}{$h16})?::)";
    $zoneid       = "(?:{$unreserved}|{$pctencoded})+";
    $ipv6addrz    = "{$ipv6address}%25{$zoneid}";

    $ipvfuture    = "(?:v[A-Fa-f0-9]+\\.(?:{$unreserved}|{$subdelims}|:)+)";
    $ipliteral    = "\\[(?:{$ipv6address}|{$ipv6addrz}|{$ipvfuture})\\]";

    $regname      = "(?:{$unreserved}|{$pctencoded}|{$subdelims})*";

    $host         = "(?:{$ipliteral}|{$ipv4address}|{$regname})";
    $port         = '[0-9]*';

    $authority    = "(?:{$userinfo}@)?{$host}(?::{$port})?";

    $pchar        = "(?:{$unreserved}|{$pctencoded}|{$subdelims}|:|@)";
    $segment      = "{$pchar}*";
    $segmentnz    = "{$pchar}+";
    $segmentnznc  = "(?:{$unreserved}|{$pctencoded}|{$subdelims}|@)+";

    $pathabempty  = "(?:\\/{$segment})*";
    $pathabsolute = "\\/(?:{$segmentnz}{$pathabempty})?";
    $pathnoscheme = "{$segmentnznc}{$pathabempty}";
    $pathrootless = "{$segmentnz}{$pathabempty}";

    $path         = "(?:{$pathabempty}|{$pathabsolute}|{$pathnoscheme}|{$pathrootless})?";

    $qfcomponent  = "(?:{$pchar}|\\/|\\?)*";

    $hierpart     = "(?:\\/\\/{$authority}{$pathabempty}|{$pathabsolute}|{$pathrootless})?";

    $uri          = "/\\A(?:({$scheme}):({$hierpart})((?:\\?{$qfcomponent})?)((?:#{$qfcomponent})?))\\z/";

    return $uri;
  }

  /**
   * Builds a regular expression to properly check/validate phone numbers provided as proper URIs
   * according to RFC3966*.
   *
   * The capture groups for the expression are as follows:
   * <ul>
   *  <li>0: complete uri</li>
   *  <li>1: scheme</li>
   *  <li>2: telephone subscriber (number)</li>
   * </ul>
   *
   * Note:
   *  The expression generated by this function does not require the 'tel' scheme at the beginning.
   *  This somewhat deviates from the syntax specified in the RFC, but allows the expression to be
   *  used in more cases than just validating URIs.
   *
   * Note:
   *  This function uses a fair amount of string concatenation to build the expression. The
   *  expression returned by this function should be stored and reused as necessary instead of
   *  making multiple calls to this function.
   *
   * @return string
   *  A regular expression capable of validating telephone subscribers, numbers and URIs.
   */
  public static function phoneURI()
  {
    $alphanum             = '[A-Za-z0-9]';
    $hexdig               = '[A-Fa-f0-9]';
    $mark                 = '[\\-_.!~*\'()]';
    $paramunreserved      = '[\\[\\]\\/:&+$]';
    $reserved             = '[;\\/?:@&=+$,]';
    $visualseparator      = '[\\-.()]';

    $domainlabel          = "(?:{$alphanum}(?:(?:{$alphanum}|-)*{$alphanum})?)";
    $pctencoded           = "(?:%{$hexdig}{$hexdig})";
    $phonedigit           = "(?:[0-9]|{$visualseparator}?)";
    $phonedigithex        = "(?:{$hexdig}|[*#]|{$visualseparator}?)";
    $pname                = "(?:(?:{$alphanum}|-)+)";
    $toplabel             = "(?:[A-Za-z](?:(?:{$alphanum}|-)*{$alphanum})?)";
    $unreserved           = "(?:{$alphanum}|{$mark})";

    $domainname           = "(?:(?:{$domainlabel}\\.)*{$toplabel}\\.?)";
    $extension            = "(?:;ext={$phonedigit}+)";
    $globalnumberdigits   = "(?:\\+{$phonedigit}*[0-9]{$phonedigit}*)";
    $localnumberdigits    = "(?:{$phonedigithex}*(?:{$hexdig}|[*#]){$phonedigithex}*)";
    $paramchar            = "(?:{$paramunreserved}|{$unreserved}|{$pctencoded})";
    $uric                 = "(?:{$reserved}|{$unreserved}|{$pctencoded})";

    $descriptor           = "(?:{$domainname}|{$globalnumberdigits})";
    $isdnsubaddress       = "(?:;isub={$uric}+)";
    $pvalue               = "(?:{$paramchar}+)";

    $context              = "(?:;phone-context={$descriptor})";
    $parameter            = "(?:;{$pname}(?:={$pvalue})?)";

    $par                  = "(?:{$parameter}|{$extension}|{$isdnsubaddress})";

    $globalnumber         = "(?:{$globalnumberdigits}{$par}*)";
    $localnumber          = "(?:{$localnumberdigits}{$par}*{$context}{$par}*)";

    $telephonesubscriber  = "(?:{$globalnumber}|{$localnumber})";

    // Impl note:
    // The "tel:" portion is optional because we're expecting semi-incomplete input. This does not
    // follow the RFC3966 specification.
    $telephoneuri         = "/\\A(tel:)?({$telephonesubscriber})\\z/i";

    return $telephoneuri;
  }
}
