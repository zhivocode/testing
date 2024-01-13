<?php

declare(strict_types=1);

namespace Zhivocode\Testing;

interface IData
{
    /**
     * Задает данные по ключу только в текущем контейнере
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return IData
     */
    public function set(string $key, mixed $value): IData;

    /**
     * Удаляет данные по ключу
     * Если в текущем контейнере отсутствуют данные по ключу,
     * но они есть в родительском контейнере по этому же ключу,
     * то будут удалены данные из родительского контейнера.
     *
     * @param string $key
     *
     * @return IData
     */
    public function unset(string $key): IData;

    /**
     * Проверяет наличие данных по ключу
     * Если в текущем контейнере отсутствуют данные по ключу,
     * но они есть в родительском контейнере по этому же ключу,
     * то флаг наличия данных будет возвращен из родительского контейнера.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isset(string $key): bool;

    /**
     * Возвращает ранее заданные данные по ключу
     * Если в текущем контейнере отсутствуют данные по ключу,
     * но они есть в родительском контейнере по этому же ключу,
     * то данные будут возвращены из родительского контейнера.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed;
}
