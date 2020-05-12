<?php

namespace App\Representation;

use JMS\Serializer\Annotation as JMS;

class AnimalsPagination extends EntitiesPagerRepresentation
{
    /**
     * @JMS\Type("array<App\Entity\Animal>")
     * @JMS\Groups({"animal", "animals"})
     */
    public $data;
    /**
     * @JMS\Groups({"animal", "animals"})
     */
    public $meta;
}