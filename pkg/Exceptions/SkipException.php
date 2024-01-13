<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Exceptions;

use Exception;

/**
 * Исключение для прерывания теста и его пропуск
 */
class SkipException extends Exception
{
}
