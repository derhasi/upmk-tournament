<?php

namespace undpaul\MarioKartBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/player');
    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/player/new');
    }

}
