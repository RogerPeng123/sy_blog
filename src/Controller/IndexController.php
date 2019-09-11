<?php

namespace App\Controller;

use App\Messenger\ChangeAmountMessenger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class IndexController extends AbstractController
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/change",name="change",methods={"post"})
     * @param Request $request
     * @return JsonResponse
     */
    public function change(Request $request): JsonResponse
    {
        $number = $request->get('n');

        $envelope = $this->bus->dispatch(new ChangeAmountMessenger($number));

        $handledStamp = $envelope->last(HandledStamp::class);

        return $this->json([
            'status' => 200,
            'data' => $handledStamp->getResult(),
            'message' => 'success'
        ]);
    }
}
