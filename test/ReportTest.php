<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Test;

use Zhivocode\Testing\ITest;
use Zhivocode\Testing\Report;

class ReportTest
{
    public function testFailWithoutPrefix(ITest $test)
    {
        $report = new Report();
        $report->fail('', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[31m❌ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 1\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testFailWithPrefix(ITest $test)
    {
        $report = new Report();
        $report->fail('prefix', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "prefix\e[1m\e[31m❌ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 1\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testFailCounting(ITest $test)
    {
        $report = new Report();
        $report->fail('', 'test', 'test');
        $report->fail('', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[31m❌ \e[0m\e[21mtest - test\n\e[1m\e[31m❌ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 2\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testFatallWithoutPrefix(ITest $test)
    {
        $report = new Report();
        $report->fatal('', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[91m⚡ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testFatalWithPrefix(ITest $test)
    {
        $report = new Report();
        $report->fatal('prefix', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "prefix\e[1m\e[91m⚡ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testFatalCounting(ITest $test)
    {
        $report = new Report();
        $report->fatal('', 'test', 'test');
        $report->fatal('', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[91m⚡ \e[0m\e[21mtest - test\n\e[1m\e[91m⚡ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSkipWithoutPrefix(ITest $test)
    {
        $report = new Report();
        $report->skip('', 'test', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[34m⏭ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 0\nПропущено: 1\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSkipWithPrefix(ITest $test)
    {
        $report = new Report();
        $report->skip('prefix', 'test', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "prefix\e[1m\e[34m⏭ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 0\nПропущено: 1\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSkipCounting(ITest $test)
    {
        $report = new Report();
        $report->skip('', 'test', 'test');
        $report->skip('', 'test', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[34m⏭ \e[0m\e[21mtest - test\n\e[1m\e[34m⏭ \e[0m\e[21mtest - test\n------\nУспешно: 0\nОшибок: 0\nПропущено: 2\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSuccessWithoutPrefix(ITest $test)
    {
        $report = new Report();
        $report->success('', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[32m✔ \e[0m\e[21mtest\n------\nУспешно: 1\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSuccessWithPrefix(ITest $test)
    {
        $report = new Report();
        $report->success('prefix', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "prefix\e[1m\e[32m✔ \e[0m\e[21mtest\n------\nУспешно: 1\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSuccessCounting(ITest $test)
    {
        $report = new Report();
        $report->success('', 'test');
        $report->success('', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[32m✔ \e[0m\e[21mtest\n\e[1m\e[32m✔ \e[0m\e[21mtest\n------\nУспешно: 2\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSubtestWithoutPrefix(ITest $test)
    {
        $report = new Report();
        $report->subtest('', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[34m↳ \e[0m\e[21mtest\n------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSubtestWithPrefix(ITest $test)
    {
        $report = new Report();
        $report->subtest('prefix', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "prefix\e[1m\e[34m↳ \e[0m\e[21mtest\n------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testSubtestCounting(ITest $test)
    {
        $report = new Report();
        $report->subtest('', 'test');
        $report->subtest('', 'test');

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "\e[1m\e[34m↳ \e[0m\e[21mtest\n\e[1m\e[34m↳ \e[0m\e[21mtest\n------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }

    public function testOutputCode(ITest $test)
    {
        $report = new Report();

        if ($report->getOutputCode() !== 0) {
            $test->fail('Ожидался код ошибки "0", вернулось - "%s"', $report->getOutputCode());
        }

        $report->fail('', 'test', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }

        $report->success('', 'test');

        if ($report->getOutputCode() !== 1) {
            $test->fail('Ожидался код ошибки "1", вернулось - "%s"', $report->getOutputCode());
        }
    }

    public function testDisplay(ITest $test)
    {
        $report = new Report();

        ob_start();
        $report->display();
        $output = ob_get_contents();
        ob_end_clean();

        if ($output !== "------\nУспешно: 0\nОшибок: 0\nПропущено: 0\n------\n") {
            $test->fail('Выводимое сообщение не соответствует ожидаемому');
        }
    }
}
