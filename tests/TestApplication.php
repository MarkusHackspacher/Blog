<?php
require_once 'core/application.php';

class ApplicationTest extends PHPUnit\Framework\TestCase
# Example from https://github.com/travis-ci-examples/php
{
    public function testset_exception_handler()
    {
        exit('Exit');
    }
}
?>
