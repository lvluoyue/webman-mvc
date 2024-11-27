<?php

namespace app\po;

class testPO
{
    private $name;
    private $age;
    public function __construct(string $name, int $age) {
        $this->name = $name;
        $this->age = $age;
    }
    public function get() {
        return [
            'name' => $this->name,
            'age' => $this->age
        ];
    }
}