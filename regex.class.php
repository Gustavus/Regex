<?php
class RegEx
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
   * [0] => entire date
   * [1] => month
   * [2] => date
   * [3] => year
   *
   * @return string
   */
  final public static function date()
  {
    return '{^(\d{2})[/-](\d{2})[/-](\d{4})$}';
  }

}
