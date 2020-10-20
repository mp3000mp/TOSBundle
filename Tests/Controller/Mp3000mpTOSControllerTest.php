<?php

namespace mp3000mp\TOSBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Mp3000mpTOSControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client;

    public function testIndex()
    {
        $this->client = static::createClient();

        $this->client->request('GET', '/tos/');

        file_put_contents(__DIR__.'/test.html', $this->client->getResponse()->getContent());

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
