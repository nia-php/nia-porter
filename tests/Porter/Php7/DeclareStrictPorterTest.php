<?php
/*
 * This file is part of the nia porter.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\NiaPorter\Porter\Php7;

use PHPUnit_Framework_TestCase;
use NiaPorter\Porter\Php7\DeclareStrictPorter;

/**
 * Unit test for \NiaPorter\Porter\Php7\DeclareStrictPorter.
 */
class DeclareStrictPorterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \NiaPorter\Porter\Php7\DeclareStrictPorter::port
     */
    public function testPort()
    {
        $actual = <<<EOL
<?php
declare(strict_types = 1);
namespace PorterTest;

class Foobar
{
}
EOL;

        $expected = <<<EOL
<?php

namespace PorterTest;

class Foobar
{
}
EOL;

        $porter = new DeclareStrictPorter();
        $this->assertSame($expected, $porter->port($actual));
    }
}

