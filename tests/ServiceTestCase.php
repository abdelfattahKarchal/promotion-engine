<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceTestCase extends WebTestCase
{

    protected ContainerInterface $container;
    // Obtain an container
    public function setUp(): void
    {
        parent::setUp();
        $this->container = static::createClient()->getContainer();
    }
}
