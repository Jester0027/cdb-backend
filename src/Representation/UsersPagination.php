<?php

namespace App\Representation;

use JMS\Serializer\Annotation as JMS;

class UsersPagination extends EntitiesPagerRepresentation
{
    /**
     * @JMS\Type("array<App\Entity\User>")
     * @JMS\Groups({"user", "users"})
     */
    public $data;
    /**
     * @JMS\Groups({"user", "users"})
     */
    public $meta;
}