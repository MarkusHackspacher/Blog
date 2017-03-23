<?php
require_once 'core/functions.php';
require_once 'core/application.php';
require_once 'core/namespace/Router.php';

class ClassTest extends PHPUnit\Framework\TestCase
{
    public function testClassParsedown()
    {
        $Parsedown = new Parsedown();
    }

    public function testClassRouter()
    {
        $Router = new Router();
    }
}
?>
