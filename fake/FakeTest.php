<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Fake;

use Zhivocode\Testing\IData;
use Zhivocode\Testing\ITest;

class FakeTest
{
    public function testFake(ITest $test, IData $data)
    {
        if ($data->get('isFailTest') === true) {
            $test->fail('fake');
        }
    }
}
