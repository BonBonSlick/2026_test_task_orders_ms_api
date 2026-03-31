<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\CQRS\Command\UseCase\CreateOrder;
use App\Application\CQRS\Query\UseCase\GetOrderList;
use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Order\Order;
use App\Infrastructure\Validation\CreateOrderDTO;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/order', name: 'order.')]
#[OA\Tag(name: 'Order', description: 'Order management')]
final class OrderController extends AbstractController
{

    public function __construct(
        protected readonly IOrderRepository    $orderRepository,
        protected readonly MessageBusInterface $commandBus,
        protected readonly MessageBusInterface $queryBus,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    #[OA\Post(
        path       : '/order/create',
        summary    : 'Create a new order',
        requestBody: new OA\RequestBody(
            required: true,
            content : new OA\JsonContent(ref: new Model(type: CreateOrderDTO::class)),
        ),
        tags       : ['Order'],
        responses  : [
            new OA\Response(
                response   : 201,
                description: 'Order created',
                content    : new OA\JsonContent(ref: new Model(type: Order::class)),
            ),
        ]
    )]
    #[Route('/create', name: 'create', methods: [Request::METHOD_POST])]
    public function create(#[MapRequestPayload] CreateOrderDTO $dto): JsonResponse {
        $this->commandBus->dispatch(
            new CreateOrder(
                productID   : $dto->productID,
                customerName: $dto->customerName,
                quantity    : $dto->quantity,
            ),
        );

        return $this->json(data: ['message' => 'Created.', 'status' => 'success'], status: Response::HTTP_CREATED);
    }

    /**
     * @throws ExceptionInterface
     */
    #[OA\Get(
        path     : '/order/list',
        summary  : 'Get list of orders',
        tags     : ['Order'],
        responses: [
            new OA\Response(
                response   : 200,
                description: 'List of orders',
                content    : new OA\JsonContent(
                                 type : 'array',
                                 items: new OA\Items(ref: new Model(type: Order::class, groups: ['main'])),
                             ),
            ),
        ]
    )]
    #[Route('/list', name: 'list', methods: [Request::METHOD_GET])]
    public function list(): JsonResponse {
        return $this->json(
            data   : $this->queryBus
                         ->dispatch(new GetOrderList())
                         ->last(HandledStamp::class)
                         ->getResult(),
            context: ['groups' => ['main']],
        );
    }

    #[OA\Get(
        path      : '/order/{id}',
        summary   : 'Get order by ID',
        tags      : ['Order'],
        parameters: [
            new OA\Parameter(
                name    : 'id',
                in      : 'path',
                required: true,
                schema  : new OA\Schema(type: 'string', format: 'uuid'),
            ),
        ],
        responses : [
            new OA\Response(
                response   : 200,
                description: 'Order details',
                content    : new OA\JsonContent(ref: new Model(type: Order::class)),
            ),
        ]
    )]
    #[Route('/{id}', name: 'info', methods: [Request::METHOD_GET])]
    public function show(Order $order): JsonResponse {
        return $this->json(
            data   : $order,
            context: ['groups' => ['main', 'info']],
        );
    }

}
