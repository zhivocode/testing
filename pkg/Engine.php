<?php

declare(strict_types=1);

namespace Zhivocode\Testing;

use Closure;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RegexIterator;
use Zhivocode\Testing\Exceptions\FatalException;
use Zhivocode\Testing\Exceptions\MissingDirectoryException;

final class Engine
{
    private Report $report;
    /**
     * @var Test[]
     */
    private array    $stack      = [];
    private ?Closure $beforeEach = null;
    private ?Closure $afterEach  = null;

    public function __construct(private readonly ?IData $container = null)
    {
        $this->report = new Report();
    }

    /**
     * Задает функцию, которая будет выполняться перед каждым тестом
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function beforeEach(Closure $callback): Engine
    {
        $this->beforeEach = $callback;

        return $this;
    }

    /**
     * Устанавливает функцию теста
     *
     * @param string  $name
     * @param Closure $testCase
     *
     * @return $this
     */
    public function test(string $name, Closure $testCase): Engine
    {
        $this->stack[] =
            new Test(
                prefix:    '',
                name:      $name,
                testCase:  $testCase,
                report:    $this->report,
                container: new Data($this->container),
                before:    $this->beforeEach,
                after:     $this->afterEach
            );

        return $this;
    }

    /**
     * Задает функцию, которая будет выполняться после каждого теста
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function afterEach(Closure $callback): Engine
    {
        $this->afterEach = $callback;

        return $this;
    }

    /**
     * Запускает выполнение тестов и возвращает код выхода
     *
     * @param array $options Опции запуска.
     *                       Доступные опции: <b>filter</b> - фильтрует выполняемые тесты
     *                       ```
     *                       [
     *                       'filter' => 'FooClass::barMethod::nestedTest'
     *                       ]
     *                       ```
     *
     * @return int
     */
    public function run(array $options = []): int
    {
        if (php_sapi_name() !== 'cli') {
            return 64;
        }

        try {
            foreach ($this->stack as $test) {
                $test->run($options['filter'] ?? null);
            }
            // phpcs:ignore
        } catch (FatalException) {
            // Если возникает отчет о фатальной ошибке,
            // то дальнейшее тестирование прекращается,
            // а в консоль выводятся отчеты выполненных тестов
        }

        $this->report->display();

        return $this->report->getOutputCode();
    }

    /**
     * Загружает тесты из указанной директории
     *
     * @throws ReflectionException
     * @throws MissingDirectoryException
     */
    public function load(string $directory): Engine
    {
        $classes = $this->loadClassesFromDirectory($directory);

        foreach ($classes as $class) {
            $this->loadTestClass($class);
        }

        return $this;
    }

    /**
     * @throws MissingDirectoryException
     */
    private function loadClassesFromDirectory(string $directory): array
    {
        if (! file_exists($directory)) {
            throw new MissingDirectoryException($directory);
        }

        $predeclaredClasses = get_declared_classes();

        $iterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, FileSystemIterator::SKIP_DOTS)
            ),
            '/^.+Test.php$/i',
            RegexIterator::GET_MATCH
        );

        foreach ($iterator as $files) {
            foreach ($files as $file) {
                require_once $file;
            }
        }

        return array_diff(get_declared_classes(), $predeclaredClasses);
    }

    /**
     * @throws ReflectionException
     */
    private function loadTestClass(string $class): void
    {
        $reflectionTestClass = new ReflectionClass($class);

        $testCase = $reflectionTestClass->newInstanceWithoutConstructor();

        if ($reflectionTestClass->hasMethod('before')) {
            $this->beforeEach(fn(IData $data) => $testCase->before($data));
        }

        if ($reflectionTestClass->hasMethod('after')) {
            $this->afterEach(fn(IData $data) => $testCase->after($data));
        }

        $this->test(
            $reflectionTestClass->getName(),
            fn(ITest $test) => $this->loadTestCase($test, $reflectionTestClass, $testCase)
        );
    }

    private function loadTestCase(ITest $test, ReflectionClass $reflectionTestClass, object $testCase): void
    {
        $beforeEach = null;
        if ($reflectionTestClass->hasMethod('beforeEach')) {
            $beforeEach = fn(IData $data) => $testCase->beforeEach($data);
        }

        $afterEach = null;
        if ($reflectionTestClass->hasMethod('afterEach')) {
            $afterEach = fn(IData $data) => $testCase->afterEach($data);
        }

        $methods = $reflectionTestClass->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $methodName = $method->getShortName();
            if (str_starts_with($methodName, 'test')) {
                $test->test(
                    $methodName,
                    fn(ITest $test, IData $data) => $testCase->$methodName($test, $data),
                    $beforeEach,
                    $afterEach
                );
            }
        }
    }
}
