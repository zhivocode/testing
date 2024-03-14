<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Test;

use Zhivocode\Testing\Data;
use Zhivocode\Testing\Exceptions\FatalException;
use Zhivocode\Testing\ITest;
use Zhivocode\Testing\Report;
use Zhivocode\Testing\Test;

class TestTest
{
    public function testRunSuccess(ITest $test)
    {
        $report = new Report();

        $testingObj = new Test(
            'test',
            'test',
            function (ITest $test) {
            },
            $report,
            new Data()
        );

        ob_start();
        $testingObj->run();
        ob_end_clean();

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }
    }

    public function testRunFail(ITest $test)
    {
        $report = new Report();

        $testingObj = new Test(
            'test',
            'test',
            function (ITest $test) {
                $test->fail('test');
            },
            $report,
            new Data()
        );

        ob_start();
        $testingObj->run();
        ob_end_clean();

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }
    }

    public function testSubtestSuccess(ITest $test)
    {
        $report = new Report();

        $testingObj = new Test(
            'test',
            'test',
            function (ITest $test) {
            },
            $report,
            new Data()
        );

        $testingObj->test(
            'subtest',
            function (ITest $test) {
            }
        );

        ob_start();
        $testingObj->run();
        ob_end_clean();

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }
    }

    public function testSubtestFail(ITest $test)
    {
        $report = new Report();

        $testingObj = new Test(
            'test',
            'test',
            function (ITest $test) {
            },
            $report,
            new Data()
        );

        $testingObj->test(
            'subtest',
            function (ITest $test) {
                $test->fail('subtest');
            }
        );

        ob_start();
        $testingObj->run();
        ob_end_clean();

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }
    }

    public function testRunFatal(ITest $test)
    {
        $report = new Report();

        $testingObj = new Test(
            'test',
            'test',
            function (ITest $test) {
                $test->fatal('test');
            },
            $report,
            new Data()
        );

        ob_start();

        $testingObj->expectException(
            FatalException::class,
            fn() => $test->fail('Ожидалось исключение "FatalException", но оно не возникло')
        );

        $testingObj->run();

        ob_end_clean();

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }
    }

    public function testRunSkip(ITest $test)
    {
        $report = new Report();

        $testingObj = new Test(
            'test',
            'test',
            function (ITest $test) {
                $test->skip('test');
            },
            $report,
            new Data()
        );

        ob_start();
        $testingObj->run();
        ob_end_clean();

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }
    }
}
