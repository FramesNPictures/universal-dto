<?php

namespace Fnp\Dto\Set;

use Fnp\Dto\Common\Helper\Obj;

class SetModel
{
    protected $_handle;

    public static function make($handle)
    {
        $object = new static($handle);

        return $object;
    }

    public function __construct($handle)
    {
        $this->_handle = $handle;
        $this->build($handle);
    }

    public static function has($handle)
    {
        $constants = self::constants();

        return in_array($handle, array_values($constants));
    }

    public static function all()
    {
        $constants = self::constants();

        $constants = array_map(function ($value) {
            return new static($value);
        }, $constants);

        return $constants;
    }

    /**
     * @param $pluckValue
     *
     * @return \Generator|array
     */
    public static function pluck($pluckValue, $pluckKey = NULL)
    {
        $pluck = [];

        /** @var SetModel $map */
        foreach (static::all() as $map) {

            $key = $map->handle();

            if ($pluckKey) {
                $key = $map->$pluckKey;
            }

            $pluck[ $key ] = $map->$pluckValue;
        }

        return $pluck;
    }

    public static function constants()
    {
        try {
            $reflection = new \ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            return [];
        }

        $constants = $reflection->getConstants();

        return $constants;
    }

    protected function build($handle)
    {
        $constants = self::constants();
        $handles   = array_flip($constants);
        $method    = Obj::methodName('set', $handles[ $handle ]);

        if (method_exists($this, $method)) {
            $this->$method();
        }

        try {
            $reflection = new \ReflectionClass($this);
        } catch (\ReflectionException $e) {
            return;
        }

        $variables  = $reflection->getProperties();

        foreach ($variables as $variable) {
            $varName = $variable->getName();
            $method  = Obj::methodName('get', $varName);

            if (!method_exists($this, $method))
                continue;

            $values = $this->$method();

            if (!isset($values[ $handle ]))
                continue;

            $this->$varName = $values[ $handle ];
        }
    }

    public function is($handle)
    {
        return $this->_handle == $handle;
    }

    public function handle()
    {
        return $this->_handle;
    }
}