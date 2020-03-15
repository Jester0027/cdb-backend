<?php

namespace App\Representation;

use JMS\Serializer\Annotation as JMS;

class EventsPagination extends EntitiesPagerRepresentation
{
    /**
     * @JMS\Type("array<App\Entity\Event>")
     * @JMS\Groups({"event"})
     */
    public $data;
    /**
     * @JMS\Groups({"event"})
     */
    public $meta;
}