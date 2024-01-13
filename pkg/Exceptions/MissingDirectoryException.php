<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Exceptions;

use Exception;

/**
 * Исключение возникающее при отсутствующей или недоступной директории
 */
class MissingDirectoryException extends Exception
{
    public function __construct(string $directoryPath)
    {
        parent::__construct(sprintf('Директория "%s" не доступна или не существует', $directoryPath));
    }
}
