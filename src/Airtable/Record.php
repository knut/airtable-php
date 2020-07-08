<?php declare(strict_types=1);

namespace Airtable;

/**
 * Airtable API Record class
 */
class Record implements \ArrayAccess
{
    private $id;

    private $fields;

    public function __construct(string $id, array $fields)
    {
        $this->id = $id;
        $this->fields = $fields;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function __set(string $name, $value) : void
    {
        $this->fields[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->fields[$name];
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->fields[] = $value;
        } else {
            $this->fields[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->fields[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->fields[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->fields[$offset]) ? $this->fields[$offset] : null;
    }

    public function toArray()
    {
        return ['id' => $this->id, 'fields' => $this->fields];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

}

