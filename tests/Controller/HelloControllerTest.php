<?php

namespace Controller;

use Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }


    public function testHelloRoute(): void
    {
        //wykonuje zadania aplikacji - ten createClient() i sie na nowo wywoluje wiec nie mamy ;smieci' z poprzednich testow
 //       given
//        $expectedContent = 'Hello World';
        $client = static::createClient();

        //when
//        $client->request('GET', '/hello');
//        $resultStatusCode = $client->getResponse()->getStatusCode();
        $client->request('GET', '/hello/John');

        //then
        $result = $client->getResponse()->getContent();
        $this->assertEquals(200, $result);
///        $this->assertSelectorTextContains('html title', 'Hello World!');
///        $this->assertSelectorTextContains('html p', 'Hello World!');
    }
        public function testDefaultGreetings(): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello');

       //then
        $this->assertSelectorTextContains('html title', 'Hello John!');
        $this->assertSelectorTextContains('html p', 'Hello John!');
    }
    /**
     * Test pesonalized greetings.
     *
     * @param string $name              Name
     * @param string $expectedGreetings Expected greetings
     *
     * @dataProvider dataProviderForTestPersonalizedGreetings
     */
    public function testPersonalizedGreetings(string $name, string $expectedGreetings): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello/'.$name);

        // then
        $this->assertSelectorTextContains('html title', $expectedGreetings);
        $this->assertSelectorTextContains('html p', $expectedGreetings);
    }




    /**
     * Data provider for testPersonalizedGreetings() method.
     *
     * @return Generator Test case
     */
    public function dataProviderForTestHelloWorldPersonalized(): Generator
    {
        yield 'Hello Ann' => [
            'name' => 'Ann',
            'expectedGreetings' => 'Hello Ann!',
        ];
        yield 'Hello John' => [
            'name' => 'John',
            'expectedGreetings' => 'Hello John!',
        ];
        yield 'Hello Beth' => [
            'name' => 'Beth',
            'expectedGreetings' => 'Hello Beth!',
        ];
    }
}

