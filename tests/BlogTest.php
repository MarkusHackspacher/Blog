<?php
require_once 'core/functions.php';

class BlogTest extends PHPUnit_Framework_TestCase
# Example from https://github.com/travis-ci-examples/php
{
    /**
     * @var PDO
     */
    private $pdo;

    public function setUp()
    {
        $this->pdo = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_username'], $GLOBALS['db_password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("CREATE TABLE hello (what VARCHAR(50) NOT NULL)");
    }

    public function tearDown()
    {
        $this->pdo->query("DROP TABLE hello");
    }

    public function testmakeSlugURL()
    {
        $test = makeSlugURL('http://Test.de');
        $this->assertEquals('http-test-de', $test);
        $test = makeSlugURL('http://äüö.de');
        $this->assertEquals('http-aeueoe-de', $test);
    }

    public function testexcerpt()
    {
        $test = excerpt('abcdefg', 3);
        $this->assertEquals('abcdefg', $test);
        $test = excerpt('abcdefg abcdef abcde', 3);
        $this->assertEquals('abcdefg abcdef abcde', $test);
        $test = excerpt('Simple test string.', 10);
        $this->assertEquals('Simple  […]', $test);
        $test = excerpt('Simple longer <br />test string.');
        $this->assertEquals('Simple longer test string.', $test);
        $test = excerpt('This is just a <strong>test</strong> string!", 20');
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
} 
?>
