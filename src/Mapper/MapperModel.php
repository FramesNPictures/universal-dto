<?php

namespace Fnp\Dto\Mapper;

use Fnp\Dto\Common\DtoToArray;
use Fnp\Dto\Flex\DtoModel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class MapperModel extends DtoModel implements Arrayable
{
    use DtoToArray;

    public function populateItems($items)
    {
        if ($items instanceof Arrayable) {
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
            $value = Arr::get($items, $targetVar);

            if ($value) {
                $setter = $this->methodExists('set', $var);

                if ($setter) {
                    $this->$setter($value);
                    continue;
                }

                $this->$var = $value;
                continue;
            }

            $this->$var = NULL;
        }
    }
}