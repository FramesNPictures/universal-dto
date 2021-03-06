<?php

namespace Fnp\Dto\Flex;

use Fnp\Dto\Collection\DtoCollectionFactory;
use Fnp\Dto\Common\DtoFill;
use Fnp\Dto\Common\DtoToArray;
use Fnp\Dto\Common\DtoToJson;
use Fnp\Dto\Contract\DtoModelContract;
use Fnp\Dto\Exception;
use Tightenco\Collect\Contracts\Support\Arrayable;
use Tightenco\Collect\Support\Collection;

abstract class DtoModel implements DtoModelContract, Arrayable
{
    use DtoToArray;
    use DtoToJson;
    use DtoFill;

    /**
     * Make model with initial data
     *
     * @param mixed      $items
     * @param null|array $flags
     *
     * @return $this
     */
    public static function make($items = NULL, $flags = NULL)
    {
        $instance = new static;

        $instance->fill($items, $flags);

        return $instance;
    }

    /**
     * Make model collection with initial data
     *
     * @param mixed      $items Data
     * @param string     $key   For associative collection provide a model key
     * @param null|array $flags
     *
     * @return Collection|null
     */
    public static function collection($items, $key = NULL, $flags = NULL)
    {
        try {
            return DtoCollectionFactory::make(get_called_class(), $items, $key, $flags);
        } catch (Exception\DtoClassNotExistsException $e) {
            return new Collection([]);
        }
    }
}