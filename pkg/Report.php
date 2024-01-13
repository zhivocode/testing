<?php

declare(strict_types=1);

namespace Zhivocode\Testing;

final class Report
{
    private array $messages     = [];
    private int   $countSuccess = 0;
    private int   $countFail    = 0;
    private int   $countSkip    = 0;
    private bool  $isFatal      = false;

    /**
     * Добавляет в отчет сообщение о неудачном тестировании
     * и увеличивает счетчик неудачных тестов
     *
     * @param string           $prefix
     * @param string           $name
     * @param string           $message
     * @param string|int|float ...$arguments
     *
     * @return void
     */
    public function fail(string $prefix, string $name, string $message, string|int|float ...$arguments): void
    {
        $this->messages[] = $prefix . "\e[1m\e[31m❌ \e[0m\e[21m$name - " . sprintf($message, ...$arguments);
        $this->countFail++;
    }

    /**
     * Добавляет в отчет сообщение о фатальной ошибке
     *
     * @param string           $prefix
     * @param string           $name
     * @param string           $message
     * @param string|int|float ...$arguments
     *
     * @return void
     */
    public function fatal(string $prefix, string $name, string $message, string|int|float ...$arguments): void
    {
        $this->messages[] = $prefix . "\e[1m\e[91m⚡ \e[0m\e[21m$name - " . sprintf($message, ...$arguments);
        $this->isFatal    = true;
    }

    /**
     * Добавляет в отчет сообщение о причине пропуска теста
     * и увеличивает счетчик пропущенных тестов
     *
     * @param string           $prefix
     * @param string           $name
     * @param string           $message
     * @param string|int|float ...$arguments
     *
     * @return void
     */
    public function skip(string $prefix, string $name, string $message, string|int|float ...$arguments): void
    {
        $this->messages[] = $prefix . "\e[1m\e[34m⏭ \e[0m\e[21m$name - " . sprintf($message, ...$arguments);
        $this->countSkip++;
    }

    /**
     * Добавляет в отчет сообщение об успешном завершении теста
     * и увеличивает счетчик успешных тестов
     *
     * @param string $prefix
     * @param string $name
     *
     * @return void
     */
    public function success(string $prefix, string $name): void
    {
        $this->messages[] = $prefix . "\e[1m\e[32m✔ \e[0m\e[21m$name";
        $this->countSuccess++;
    }

    /**
     * Добавляет в отчет сообщение о начале тестирования дочерних тестов
     *
     * @param string $prefix
     * @param string $name
     *
     * @return void
     */
    public function subtest(string $prefix, string $name): void
    {
        $this->messages[] = $prefix . "\e[1m\e[34m↳ \e[0m\e[21m$name";
    }

    /**
     * Возвращает код выхода отчета
     *
     * @return int
     */
    public function getOutputCode(): int
    {
        return ($this->countFail > 0 || $this->isFatal) ? 1 : 0;
    }

    /**
     * Выводит отчет в консоль
     *
     * @return void
     */
    public function display(): void
    {
        foreach ($this->messages as $message) {
            $this->writeln($message);
        }
        $this->writeln('------');
        $this->writeln('Успешно: ' . $this->countSuccess);
        $this->writeln('Ошибок: ' . $this->countFail);
        $this->writeln('Пропущено: ' . $this->countSkip);
        $this->writeln('------');
    }

    private function writeln(string $line): void
    {
        echo $line . PHP_EOL;
    }
}
