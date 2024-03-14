<?php

declare(strict_types=1);

namespace Zhivocode\Testing;

use Closure;
use Throwable;
use Zhivocode\Testing\Exceptions\FatalException;
use Zhivocode\Testing\Exceptions\SkipException;

final class Test implements ITest
{
    private bool $hasSubtest = false;
    /**
     * @var Test[]
     */
    private array $stack   = [];
    private bool  $success = true;

    private ?string  $expectException    = null;
    private ?Closure $exceptionInspector = null;
    private bool     $exceptionInspected = false;


    public function __construct(
        private readonly string $prefix,
        private readonly string $name,
        private readonly Closure $testCase,
        private readonly Report $report,
        private readonly Data $container,
        private readonly ?Closure $before = null,
        private readonly ?Closure $after = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function test(string $name, Closure $testCase, ?Closure $before = null, ?Closure $after = null): void
    {
        $this->hasSubtest = true;
        $this->stack[]    = new Test(
            prefix:    $this->prefix . '    ',
            name:      $name,
            testCase:  $testCase,
            report:    $this->report,
            container: $this->container,
            before:    $before,
            after:     $after
        );
    }

    /**
     * @inheritDoc
     */
    public function fail(string $message, string|int|float ...$arguments): void
    {
        $this->report->fail($this->prefix, $this->name, $message, ...$arguments);
        $this->success = false;
    }

    /**
     * @inheritDoc
     * @throws FatalException
     */
    public function fatal(string $message, string|int|float ...$arguments): void
    {
        $this->report->fatal($this->prefix, $this->name, $message, ...$arguments);
        $this->success = false;

        throw new FatalException();
    }

    /**
     * @inheritDoc
     * @throws SkipException
     */
    public function skip(string $message, string|int|float ...$arguments): void
    {
        $this->report->skip($this->prefix, $this->name, $message, ...$arguments);
        $this->success = false;

        throw new SkipException();
    }

    /**
     * @inheritDoc
     */
    public function expectException(string $exceptionClass, Closure $inspector): void
    {
        $this->expectException    = $exceptionClass;
        $this->exceptionInspector = $inspector;
    }

    /**
     * Запускает тест, если он соответствует фильтру.
     *
     * @throws FatalException
     */
    public function run(?string $filter = null): void
    {
        $mainFilter = null;
        $subFilter  = null;
        if ($filter !== null) {
            $explodedFilter = array_map('trim', explode('::', $filter, 2));
            $mainFilter     = array_shift($explodedFilter);
            $subFilter      = array_shift($explodedFilter);
        }

        if ($mainFilter !== null && $mainFilter != $this->name) {
            return;
        }

        if ($this->before !== null) {
            call_user_func($this->before, $this->container);
        }

        try {
            call_user_func($this->testCase, $this, $this->container);
            // @phpcs:ignore
        } catch (SkipException) {
            // Если возникает прерывание теста,
            // то данный тест пропускается,
            // а в отчеты добавляется причина прерывания
        } catch (Throwable $exception) {
            // phpcs:ignore
            if ($this->expectException !== null && $exception instanceof $this->expectException) {
                // Если ожидается исключение
                // и возникшее исключение соответствует ожидаемому,
                // то оно игнорируется
                $this->exceptionInspected = true;
            } elseif ($this->expectException !== null && ! $exception instanceof $this->expectException) {
                ($this->exceptionInspector)($this);
            } else {
                $this->fail($exception->getMessage());
            }
        } finally {
            if ($this->expectException !== null && $this->exceptionInspected === false) {
                ($this->exceptionInspector)($this);
            }
        }

        if (! $this->hasSubtest && $this->success) {
            $this->report->success($this->prefix, $this->name);
        }

        if ($this->hasSubtest) {
            $this->report->subtest($this->prefix, $this->name);
            foreach ($this->stack as $test) {
                $test->run($subFilter);
            }
        }

        if ($this->after !== null) {
            call_user_func($this->after, $this->container);
        }
    }
}
