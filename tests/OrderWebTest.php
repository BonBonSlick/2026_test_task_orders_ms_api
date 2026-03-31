<?php

namespace App\Tests;

use JsonException;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

use function json_decode;

use const JSON_THROW_ON_ERROR;

class OrderWebTest extends WebTestCase
{

    private array $serverConfigs
        = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
        ];

    /**
     * @throws JsonException
     */
    public function testCreateOrder(): void {
        $client = static::createClient();
        $client->request(
            method    : Request::METHOD_POST,
            uri       : '/order/create',
            parameters: [
                            'productID'    => 'dcf89298-82cc-4b0e-b6bb-a41cfac6b626',
                            'customerName' => 'Test Customer',
                            'quantity'     => 5,
                        ],
            server    : $this->serverConfigs,
        );

        $response = json_decode($client->getResponse()->getContent(), false, 512, JSON_THROW_ON_ERROR);

        self::assertSame('success', $response->status);
    }

    /**
     * @throws JsonException
     */
    #[Group('orderInfo')]
    public function testOrderList(): void {
        $client = static::createClient();
        $client->request(
            method: Request::METHOD_GET,
            uri   : '/order/list',
            server: $this->serverConfigs,
        );

        $response = json_decode(
            json       : $client->getResponse()->getContent(),
            associative: false,
            depth      : 512,
            flags      : JSON_THROW_ON_ERROR,
        );

        self::assertSame(expected: 'order 0', actual: $response[0]->name);
    }

    /**
     * @throws JsonException
     */
    #[Group('orderInfo')]
    #[Depends('testOrderList')]
    public function testOrderInfo(): void {
        $client = static::createClient();
        // we have to query again because of DAMA package which wraps every test in DB transaction
        // this is not ideal but it works, there are better ways to solve double or more queries in one test though

        $client->request(
            method: Request::METHOD_GET,
            uri   : '/order/list',
            server: $this->serverConfigs,
        );

        $responseList = json_decode(
            json       : $client->getResponse()->getContent(),
            associative: false,
            depth      : 512,
            flags      : JSON_THROW_ON_ERROR,
        );
        $expectedID   = $responseList[0]->id;

        $client->request(
            method: Request::METHOD_GET,
            uri   : '/order/' . $expectedID,
            server: $this->serverConfigs,
        );

        $response = json_decode($client->getResponse()->getContent(), false, 512, JSON_THROW_ON_ERROR);

        self::assertSame($expectedID, $response->id);
    }

}
