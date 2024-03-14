<?php

declare(strict_types=1);

namespace Zhivocode\Testing;

use Closure;

interface ITest
{
    /**
     * Добавляет суб-тест
     *
     * @param string       $name
     * @param Closure      $testCase
     * @param Closure|null $before
     * @param Closure|null $after
     *
     * @return void
     */
    public function test(string $name, Closure $testCase, ?Closure $before = null, ?Closure $after = null): void;

    /**
     * Завершает тест с отчетом об ошибке
     *
     * @param string           $message
     * @param string|int|float ...$arguments
     *
     * @return void
     */
    public function fail(string $message, string|int|float ...$arguments): void;

    /**
     * Полностью останавливает тестирование с отчетом о фатальной ошибке
     *
     * @param string           $message
     * @param string|int|float ...$arguments
     *
     * @return void
     */
    public function fatal(string $message, string|int|float ...$arguments): void;

    /**
     * Прерывает и пропускает тест с отчетом причины
     *
     * @param string           $message
     * @param string|int|float ...$arguments
     *
     * @return void
     */
    public function skip(string $message, string|int|float ...$arguments): void;

    /**
     * Ожидает возникновения исключения
     *
     * @param string  $exceptionClass
     * @param Closure $inspector
     *
     * @return void
     */
    public function expectException(string $exceptionClass, Closure $inspector): void;
}
