<?php

namespace App\Serializer;

use App\Entity\Animal;
use App\Entity\AnimalCategory;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        $id = $object->getId();
        switch ($object) {
            case $object instanceof Animal:
                return "/api/animals/" . $id;
                break;
            case $object instanceof AnimalCategory:
                return "/api/animal_category/" . $id;
                break;
            default:
                return $object->getId();
        }
    }
}
