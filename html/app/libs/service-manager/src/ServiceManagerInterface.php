<?php

namespace matpoppl\ServiceManager;

interface ServiceManagerInterface extends ContainerInterface
{
    public function build(string $id, array $options = null);
}
