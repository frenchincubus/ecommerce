<?php

namespace App\Tests\Controller\FrontControllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MenuControllerTest extends WebTestCase
{
    public function testSearch()
    {
        $client = static::createClient();
        // $crawler = $client->request('GET', '/products/search/sav');
        $client->xmlHttpRequest('GET', '/products/search/sav');
        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }


}
