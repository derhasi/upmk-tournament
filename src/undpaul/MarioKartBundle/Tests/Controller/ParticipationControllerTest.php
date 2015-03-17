<?php

namespace undpaul\MarioKartBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipationControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tournament/{tournament_id}');
    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/participation/{pid}');
    }

    public function testRemove()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/participation/{pid}/remove');
    }

}
