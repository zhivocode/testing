<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Test;

use Zhivocode\Testing\Data;
use Zhivocode\Testing\Engine;
use Zhivocode\Testing\Exceptions\MissingDirectoryException;
use Zhivocode\Testing\ITest;

class EngineTest
{
    public function testBeforeEach(ITest $test)
    {
        $isCalled = false;

        $engine = new Engine();

        $engine->beforeEach(
            function () use (&$isCalled) {
                $isCalled = true;
            }
        );

        $engine->test(
            'fake',
            function () use (&$isCalled, $test) {
                if ($isCalled === false) {
                    $test->fail('Функция, которая должна быть выполнена перед тестом, не была выполнена');
                }
            }
        );

        ob_start();
        $engine->run();
        ob_end_clean();
    }

    public function testAfterEach(ITest $test)
    {
        $isCalled = false;

        $engine = new Engine();

        $engine->afterEach(
            function () use (&$isCalled) {
                $isCalled = true;
            }
        );

        $engine->test(
            'fake',
            function () use (&$isCalled, $test) {
                if ($isCalled === true) {
                    $test->fail('Функция, которая должна быть выполнена после теста, выполнена раньше');
                }
            }
        );

        ob_start();
        $engine->run();
        ob_end_clean();

        if ($isCalled === false) {
            $test->fail('Функция, которая должна быть выполнена после теста, не была выполнена');
        }
    }

    public function testAddingTest(ITest $test)
    {
        $isCalled = false;

        $engine = new Engine();

        $engine->test(
            'fake',
            function () use (&$isCalled) {
                $isCalled = true;
            }
        );

        ob_start();
        $engine->run();
        ob_end_clean();

        if ($isCalled === false) {
            $test->fail('Функция теста не была выполнена');
        }
    }

    public function testNestedTest(ITest $test)
    {
        $isCalled = false;

        $engine = new Engine();

        $engine->test(
            'fake',
            function (ITest $test) use (&$isCalled) {
                $test->test(
                    'nested',
                    function () use (&$isCalled) {
                        $isCalled = true;
                    }
                );
            }
        );

        ob_start();
        $engine->run();
        ob_end_clean();

        if ($isCalled === false) {
            $test->fail('Функция вложенного теста не была выполнена');
        }
    }

    public function testRun(ITest $test)
    {
        $isCalled = false;

        $engine = new Engine();

        $engine->test(
            'fake',
            function (ITest $test) use (&$isCalled) {
                $test->fail('fake');
                $isCalled = true;
            }
        );

        ob_start();
        $code = $engine->run();
        ob_end_clean();

        if ($isCalled === false) {
            $test->fail('Запуск тестов не был выполнен');
        }

        if ($code != 1) {
            $test->fail('Ожидалось возврат кода ошибки 1, вернулось - %s', $code);
        }
    }

    public function testLoad(ITest $test)
    {
        $data = new Data();

        $engine = new Engine($data);

        $engine->load(dirname(__DIR__) . '/fake');

        ob_start();
        $data->set('isFailTest', false);
        $code = $engine->run();
        ob_end_clean();

        if ($code != 0) {
            $test->fail('Ожидалось возврат кода ошибки 0, вернулось - %s', $code);
        }

        ob_start();
        $data->set('isFailTest', true);
        $code = $engine->run();
        ob_end_clean();

        if ($code != 1) {
            $test->fail('Ожидалось возврат кода ошибки 1, вернулось - %s', $code);
        }
    }

    public function testLoadMissingDirectory(ITest $test)
    {
        $data = new Data();

        $engine = new Engine($data);

        try {
            $engine->load(dirname(__DIR__) . '/foo/bar');
            $test->fail('Ожидалось исключение "MissingDirectoryException", которое не возникло');
            // phpcs:ignore
        } catch (MissingDirectoryException) {
            // Если возникло исключение, тест пройден
        }
    }
}
