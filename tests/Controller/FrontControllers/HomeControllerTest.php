<?php

namespace App\Tests\Controller\FrontControllers;

use PHPUnit\Framework\TestCase;
use App\Controller\FrontControllers\HomeController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Bienvenue');
        $this->assertGreaterThan(
            0,
            $crawler->filter('p:contains("Bienvenue")')->count()
        );
    }
}