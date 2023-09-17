<?php

namespace matpoppl\Hydrator;

interface HydrationInterface
{
    /**
     *
     * @param object $obj
     * @param array $data
     */
    public function hydrate($obj, array $data);
}
