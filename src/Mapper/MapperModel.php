<?php

namespace Fnp\Dto\Mapper;

use Fnp\Dto\Common\DtoToArray;
use Fnp\Dto\Flex\DtoModel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class MapperModel extends DtoModel implements Arrayable
{
    use DtoToArray;

    public function fill($items)
    {
        if (!Arr::accessible($items) && $items instanceof Arrayable) {
            $items = $items->toArray();
        }

        if ($items instanceof \stdClass) {
            $items = get_object_vars($items);
        }

        $reflection = new \ReflectionClass($this);
        $vars       = $reflection->getProperties();

        foreach ($vars as $var) {

            $var       = $var->getName();
            $targetVar = $this->$var;

            if (is_null($targetVar)) {
                continue;
            }

            $value = Arr::get($items, $targetVar, NULL);

            if ($targetVar == '.') {
                $value = $items;
            }

            $setter = $this->_methodExists('set', $var);

            if ($setter) {

                $value = $this->$setter($value);

                if (!is_null($value)) {
                    $this->$var = $value;
                } else {
                    if ($this->$var == $targetVar) {
                        $this->$var = NULL;
                    }
                }

                continue;
            }

            $this->$var = $value;
        }
    }
}