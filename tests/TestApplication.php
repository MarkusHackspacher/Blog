<?php
require_once 'core/application.php';

class TestApplication extends PHPUnit\Framework\TestCase
# Example from https://github.com/travis-ci-examples/php
{
    public function testset_exception_handler()
    {
        $test = set_exception_handler();
        $this->assertEquals(1, $test);
    }
}
