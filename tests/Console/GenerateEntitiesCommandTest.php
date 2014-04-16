<?php

namespace Atrauzzi\Tests\Console;
use Atrauzzi\LaravelDoctrine\Console\GenerateEntitiesCommand;

class GenerateEntitiesCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testExistingPath()
    {
        $generateEntities = new GenerateEntitiesCommand();
    }

}
