<?php

namespace matpoppl\Hydrator;

interface ExtractionInterface
{
    /**
     *
     * @param object $obj
     * @return array
     */
    public function extract($obj);
}
