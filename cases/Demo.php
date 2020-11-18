<?php

declare(strict_types=1);

namespace ApiTester\Cases;

class Demo extends HttpTestCase
{
    const name = 'Demo';
    
    public function testDemo()
    {
        $this->assertTrue(true);
    }
    
}