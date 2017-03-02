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
        $this->assertEquals('http-test-de', $test);
    }

    public function testexcerpt()
    {
        $test = excerpt('http://Test.de');

        $this->assertEquals('http-test-de', $test);
    }
    public function testremoveHTML()
    {
        $test = removeHTML('<script>alert('XSS');</script>');

        $this->assertEquals('&lt;script&gt;alert('XSS');&lt;/script&gt;', $test);
    }
    public function testescapeHTML()
    {
        $test = escapeHTML('&"<>');

        $this->assertEquals('&amp;&quot;&lt;&gt;', $test);
    }
} 
?>
