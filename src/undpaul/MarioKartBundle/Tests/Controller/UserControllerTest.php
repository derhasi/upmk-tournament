<?php

namespace undpaul\MarioKartBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user');
    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/new');
    }

}
