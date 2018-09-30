<?php
require_once 'core/application.php';

class StaticTest extends PHPUnit\Framework\TestCase
# Example from https://github.com/travis-ci-examples/php
{

    public function testexcerpt()
    {
        $test = excerpt('abcdefg', 3);
        $this->assertEquals('[…]', $test);
        $test = excerpt('abcdefg abcdef abcde', 3);
        $this->assertEquals('[…]', $test);
        $test = excerpt('Simple test string.', 10);
        $this->assertEquals('Simple […]', $test);
    }

    public function testexcerptlong()
    {
        $test = excerpt('Simple longer <br />test string.');
        $this->assertEquals('Simple longer test string.', $test);
        $test = excerpt('This is just a <strong>test</strong> string!', 20);
        $this->assertEquals('This is just a test […]', $test);

    }

    public function testremoveHTML()
    {
        $test = removeHTML('<script>alert("XSS");</script>');
        $this->assertEquals('alert("XSS");', $test);
    }

    public function testescapeHTML()
    {
        $test = escapeHTML('&"<>');
        $this->assertEquals('&amp;&quot;&lt;&gt;', $test);
        $test = escapeHTML('<script>alert("XSS");</script>');
        $this->assertEquals('&lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;', $test);
    }

    public function testtruncate()
    {
        $test = truncate('This is just a <strong>test</strong> string!', 20);
        $this->assertEquals('This is just a <', $test);
        $test = truncate('This is just a <strong>test</strong> string!', 10);
        $this->assertEquals('This is', $test);
    }

    # Parse emoticons to HTML encoded unicode characters
    public function testparseEmoticons()
    {
        $test = parseEmoticons('This :D is ;) just :) a test :X string 8) ! :|');
        $this->assertEquals('This <span title="Smiling face with open mouth">&#x1F603;</span> is <span title="Winking face">&#x1F609;</span> just <span title="Smiling face with smiling eyes">&#x1F60A;</span> a test <span title="Dizzy face">&#x1F635;</span> string <span title="Smiling face with sunglasses">&#x1F60E;</span> ! <span title="Neutral face">&#x1F610;</span>', $test);
        $test = parseEmoticons(':)');
        $this->assertEquals('<span title="Smiling face with smiling eyes">&#x1F60A;</span>', $test);
        $test = parseEmoticons(':(');
        $this->assertEquals('<span title="Disappointed face">&#x1F61E;</span>', $test);
        $test = parseEmoticons(':D');
        $this->assertEquals('<span title="Smiling face with open mouth">&#x1F603;</span>', $test);
        $test = parseEmoticons(':P');
        $this->assertEquals('<span title="Face with stuck-out tongue">&#x1F61B;</span>', $test);
    }


    # Parser for datetime formatted strings [YYYY-MM-DD HH:II:SS]
    public function testparseDatetime()
    {
        $test = parseDatetime('2000-01-01 12:34:56', '[W] [D] [F] [Y]');
        $this->assertEquals('Saturday 01 January 2000', $test);
        $test = parseDatetime('2027-03-24 12:34:56', '[W] [D] [F] [Y]');
        $this->assertEquals('Wednesday 24 March 2027', $test);
    }
} 
?>
 
