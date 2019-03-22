<?php

namespace Florowebdevelopment\EdifactGenerator;

class Segment
{
    protected $aComposed = [];

    /**
     * Get Composed.
     *
     * @return array $this->aComposed
     */
    public function getComposed(): array
    {
        return $this->aComposed;
    }

    /**
     * Set Composed.
     *
     * @param array $aComposed
     */
    public function setComposed(array $aComposed): void
    {
        $this->aComposed = $aComposed;
    }
}
