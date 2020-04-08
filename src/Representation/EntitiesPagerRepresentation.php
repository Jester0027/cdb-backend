<?php

namespace App\Representation;

use LogicException;
use Pagerfanta\Pagerfanta;

abstract class EntitiesPagerRepresentation
{
    public $data;
    public $meta;

    public function __construct(Pagerfanta $data)
    {
        $this->data = $data->getCurrentPageResults();

        $this->addMeta('limit', $data->getMaxPerPage());
        $this->addMeta('currentItems', count($data->getCurrentPageResults()));
        $this->addMeta('totalItems', $data->getNbResults());
        $this->addMeta('currentPage', $data->getCurrentPage());
        $this->addMeta('maxPages', $data->getNbPages());
        $this->addMeta('offset', $data->getCurrentPageOffsetStart());
    }
    
    public function addMeta($name, $value)
    {
        if (isset($this->meta[$name])) {
            throw new LogicException("this meta already exist, override it with setMeta");
        }

        $this->setMeta($name, $value);
    }

    public function setMeta($name, $value)
    {
        $this->meta[$name] = $value;
    }
}