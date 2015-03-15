<?php

namespace undpaul\MarioKartBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoundControllerTest extends WebTestCase
{
    public function testGenerate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tournament/{tournament_id}/round/generate');
    }

}
