<?php

declare(strict_types=1);

namespace Zhivocode\Testing\Test;

use ReflectionClass;
use ReflectionProperty;
use stdClass;
use Zhivocode\Testing\Data;
use Zhivocode\Testing\IData;
use Zhivocode\Testing\ITest;

class DataTest
{
    public function beforeEach(IData $data): void
    {
        $obj = new Data();
        $data->set(Data::class, $obj);
        $reflection = new ReflectionClass(get_class($obj));
        $property   = $reflection->getProperty('values');
        $data->set(ReflectionProperty::class, $property);
    }

    public function testSetData(ITest $test, IData $data): void
    {
        $obj = $data->get(Data::class);
        $obj->set('foo', 'bar');

        $property = $data->get(ReflectionProperty::class);
        $value    = $property->getValue($obj);

        if (! isset($value['foo']) || $value['foo'] !== 'bar') {
            $test->fail('Данные в контейнере не соответствуют ожидаемым');
        }
    }

    public function testGetData(ITest $test, IData $data): void
    {
        $obj = $data->get(Data::class);

        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => 'bar']);

        $value = $obj->get('foo');
        if ($value !== 'bar') {
            $test->fail('Данные полученные из контейнера не соответствуют ожидаемым');
        }
    }

    public function testGetNotExistsData(ITest $test, IData $data): void
    {
        $obj = $data->get(Data::class);

        $value = $obj->get('foo');
        if ($value !== null) {
            $test->fail('Ожидалось "null", вернулось "%s"', gettype($value));
        }
    }

    public function testGetDataString(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => 'bar']);
        $type = gettype($obj->get('foo'));

        if ($type !== 'string') {
            $test->fail('Ожидалось "string", вернулось - "%s"', $type);
        }
    }

    public function testGetDataInteger(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => 123]);
        $type = gettype($obj->get('foo'));

        if ($type !== 'integer') {
            $test->fail('Ожидалось "integer", вернулось - "%s"', $type);
        }
    }

    public function testGetDataFloat(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => 12.3]);
        $type = gettype($obj->get('foo'));

        if ($type !== 'double') {
            $test->fail('Ожидалось "double", вернулось - "%s"', $type);
        }
    }

    public function testGetDataObject(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => new stdClass()]);
        $type = gettype($obj->get('foo'));

        if ($type !== 'object') {
            $test->fail('Ожидалось "object", вернулось - "%s"', $type);
        }
    }

    public function testGetDataArray(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => ['bar']]);
        $type = gettype($obj->get('foo'));

        if ($type !== 'array') {
            $test->fail('Ожидалось "array", вернулось - "%s"', $type);
        }
    }

    public function testGetDataBoolean(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => true]);
        $type = gettype($obj->get('foo'));

        if ($type !== 'boolean') {
            $test->fail('Ожидалось "boolean", вернулось - "%s"', $type);
        }
    }

    public function testGetDataCallable(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue(
            $obj,
            [
                'foo' => function () {
                },
            ]
        );
        $type = gettype($obj->get('foo'));

        if ($type !== 'object' && is_callable($type)) {
            $test->fail('Ожидалось "callable", вернулось - "%s"', $type);
        }
    }

    public function testGetDataNull(ITest $test, IData $data)
    {
        $obj      = $data->get(Data::class);
        $property = $data->get(ReflectionProperty::class);
        $property->setValue($obj, ['foo' => null]);
        $type = gettype($obj->get('foo'));

        if (strtolower($type) !== 'null') {
            $test->fail('Ожидалось "null", вернулось - "%s"', $type);
        }
    }
}
