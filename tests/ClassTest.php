<?php
require_once 'core/functions.php';
require_once 'core/application.php';
require_once 'core/namespace/Router.php';

class ClassTest extends PHPUnit\Framework\TestCase
{
    public function testClassParsedown()
    {
        $Parsedown = new Parsedown();
        $this->assertInstanceOf(Parsedown::class, $Parsedown);
        $this->assertEquals('<p>Hello <em>Parsedown</em>!</p>',
                            $Parsedown->text('Hello _Parsedown_!'));
    }

    public function testClassRouter()
    {
        $Router = new Router();
        $this->assertInstanceOf(Router::class, $Router);
        $_SERVER['REQUEST_URI'] = '/';
        $Router->execute('https://localhost:8080/feed/');
    }
}
?>
