<?php

namespace App\Representation;

use JMS\Serializer\Annotation as JMS;

class RefugesPagination extends EntitiesPagerRepresentation
{
    /**
     * @JMS\Type("array<App\Entity\Refuge>")
     * @JMS\Groups({"refuge", "refuges"})
     */
    public $data;
    /**
     * @JMS\Groups({"refuge", "refuges"})
     */
    public $meta;
}