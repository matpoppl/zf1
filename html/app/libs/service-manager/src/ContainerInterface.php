<?php

namespace matpoppl\ServiceManager;

interface ContainerInterface
{
    public function has(string $id): bool;
    public function get(string $id);
}
