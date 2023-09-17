<?php

namespace matpoppl\ServiceManager;

interface ContainerAwareInterface
{
    public function setContainer(ContainerInterface $container);
}
