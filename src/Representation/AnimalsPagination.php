<?php

namespace App\Representation;

use JMS\Serializer\Annotation as JMS;

class AnimalsPagination extends EntitiesPagerRepresentation
{
    /**
     * @JMS\Type("array<App\Entity\Animal>")
     * @JMS\Groups({"animal"})
     */
    public $data;
    /**
     * @JMS\Groups({"animal"})
     */
    public $meta;
}