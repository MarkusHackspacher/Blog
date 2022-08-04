<?php
require_once 'core/application.php';

class BlogTest extends PHPUnit\Framework\TestCase
# Example from https://github.com/travis-ci-examples/php
{
    /**
     * @var PDO
     */
    private $pdo;

    public function setUp(): void
    {
        $this->pdo = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_username'], $GLOBALS['db_password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("CREATE TABLE hello (what VARCHAR(50) NOT NULL)");
    }

    public function tearDown(): void
    {
        $this->pdo->query("DROP TABLE hello");
    }

    # Test functions.php generateItemTemplateData
    public function testgenerateItemTemplateData()
    {
        $Page = Template\Factory::build(1);
        $User = Template\Factory::build($Page->attr('user'));

        $test = generateItemTemplateData($Page, $User);
        $testpage = $test->get('PAGE');
        # print_r($testpage);
        $this->assertEquals(1 , $testpage['ATTR']['ID']);
        $this->assertEquals('https://localhost:8080/page/example-page/' , $testpage['URL']);
        $testuser = $test->get('USER');
        # print_r($testuser);
        $this->assertEquals(1 , $testuser['ATTR']['ID']);
        $this->assertEquals('https://localhost:8080/user/change-me/' , $testuser['URL']);

        $test = generateUserItemTemplate($User);

        $Post = Template\Factory::build(1);
        $User = Template\Factory::build($Post->attr('user'));
        $test = generatePostItemTemplate($Post, $User);

        $page_data = generateItemTemplateData($Page);
        $post_data = generateItemTemplateData($Post);
        $user_data = generateItemTemplateData($User);
    }

    public function testgenerateSlug()
    {
        $test = generateSlug('http://Test.de');
        $this->assertEquals('http-test-de', $test);
        $test = generateSlug('http://äüö.de');
        $this->assertEquals('http-aeueoe-de', $test);
        $this->assertEquals('a-simple-slug-test', generateSlug('A simple Slug Test!'));
        $this->assertEquals('dies-ist-ein-supercooler-blogpost',
                            generateSlug('Dies ist ein supercooler Blogpost!'));
        $this->assertEquals('se-r-sumer-orateur', generateSlug('se résumer orateur:'));
        $this->assertEquals('', generateSlug('           '));
        $this->assertEquals('u-z', generateSlug('ûûûuûûû  ^z')); 
        $this->assertEquals('', generateSlug('ûôûôûôûââ')); 
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

} 
?>
