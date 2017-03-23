<?php
require_once 'core/functions.php';
require_once 'core/application.php';

class BlogTest extends PHPUnit\Framework\TestCase
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

    # Helper function to reduce duplicate code
    public function testgeneratePageNaviTemplate()
    {
        $test = generatePageNaviTemplate(1);
        $this->assertEquals(1 , $test->get('THIS'));
        $this->assertEquals(1 , $test->get('LAST'));
        $this->assertEquals('https://localhost:8080/page/?site=%d' , $test->get('HREF'));
    }

    # Helper function to reduce duplicate code
    public function testgeneratePostNaviTemplate()
    {
        $test = generatePostNaviTemplate(1);
        $this->assertEquals(1 , $test->get('THIS'));
        $this->assertEquals(1 , $test->get('LAST'));
        $this->assertEquals('https://localhost:8080/post/?site=%d' , $test->get('HREF'));
    }

    # Helper function to reduce duplicate code
    public function testgenerateUserNaviTemplate()
    {
        $test = generateUserNaviTemplate(1);
        $this->assertEquals(1 , $test->get('THIS'));
        $this->assertEquals(1 , $test->get('LAST'));
        $this->assertEquals('https://localhost:8080/user/?site=%d' , $test->get('HREF'));
    }

    # Helper function to reduce duplicate code
    public function testgenerateItemTemplate()
    {
        $Page = Page\Factory::build(1);
        $User = User\Factory::build($Page->attr('user'));

        $test = generatePageItemTemplate($Page, $User);
        $testpage = $test->get('PAGE');
        $this->assertEquals(1 , $testpage['ID']);
        $this->assertEquals('https://localhost:8080/page/example-page/' , $testpage['URL']);
        $testuser = $test->get('USER'));
        $this->assertEquals(1 , $testuser['ID']);
        $this->assertEquals('https://localhost:8080/user/change-me/' , $testuser['URL']);
        $test = generatePostItemTemplate($Page, $User);
        $test = generateUserItemTemplate($User);
        $page_data = generatePageItemData($Page);
        $user_data = generateUserItemData($User);
    }

    public function testmakeSlugURL()
    {
        $test = makeSlugURL('http://Test.de');
        $this->assertEquals('http-test-de', $test);
        $test = makeSlugURL('http://äüö.de');
        $this->assertEquals('http-aeueoe-de', $test);
        $this->assertEquals('a-simple-slug-test', makeSlugURL('A simple Slug Test!'));
        $this->assertEquals('dies-ist-ein-supercooler-blogpost',
                            makeSlugURL('Dies ist ein supercooler Blogpost!'));
        $this->assertEquals('se-r-sumer-orateur', makeSlugURL('se résumer orateur:'));
        $this->assertEquals('', makeSlugURL('           '));
        $this->assertEquals('u-z', makeSlugURL('ûûûuûûû  ^z')); 
        $this->assertEquals('', makeSlugURL('ûôûôûôûââ')); 
    }

    public function testDBfetchHello()
    {
        $sth = $this->pdo->prepare("SELECT * FROM hello");
        $sth->execute();
        $this->assertEquals(Array (), $sth->fetchAll());
    }

    public function testDBfetch()
    {
        $sth = $this->pdo->prepare("SELECT id FROM page");
        $sth->execute();
        $test = $sth->fetchAll();
        $this->assertEquals(Array(Array('id' => 1, 1)), $test);

        $sth = $this->pdo->prepare("SELECT id FROM post");
        $sth->execute();
        $this->assertEquals(Array(Array('id' => 1, 1)), $sth->fetchAll());

        $sth = $this->pdo->prepare("SELECT id FROM user");
        $sth->execute();
        $this->assertEquals(Array(Array('id' => 1, 1)), $sth->fetchAll());
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
        $test = excerpt('This is just a <strong>test</strong> string!', 20);
        $this->assertEquals('This is just a test  […]', $test);

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

    public function testcut()
    {
        $test = cut('This is just a <strong>test</strong> string!', 20);
        $this->assertEquals('This is just a < […]', $test);
    }

    # Parse emoticons to HTML encoded unicode characters
    public function testparseEmoticons()
    {
        $test = parseEmoticons('This :D is ;) just :) a test :X string 8) ! :|');
        $this->assertEquals('This <span title="Smiling face with open mouth">&#x1F603;</span> is <span title="Winking face">&#x1F609;</span> just <span title="Smiling face with smiling eyes">&#x1F60A;</span> a test <span title="Dizzy face">&#x1F635;</span> string <span title="Smiling face with sunglasses">&#x1F60E;</span> ! <span title="Neutral face">&#x1F610;</span>', $test);
        $test = parseEmoticons(':)');
        $this->assertEquals(' <span title="Smiling face with smiling eyes">&#x1F60A;</span>', $test);
        $test = parseEmoticons(':(');
        $this->assertEquals(' <span title="Disappointed face">&#x1F61E;</span>', $test);
        $test = parseEmoticons(':D');
        $this->assertEquals(' <span title="Smiling face with open mouth">&#x1F603;</span>', $test);
        $test = parseEmoticons(':P');
        $this->assertEquals(' <span title="Face with stuck-out tongue">&#x1F61B;</span>', $test);
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
