<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\TransactionHandler\Exception\InsufficientFundsException;
use App\TransactionHandler\TransactionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction")
     */
    public function index(
        TransactionHandler $transactionHandler,
        Request $request,
        SerializerInterface $serializer
    )
    {
        /** @var Transaction $transaction */
        $transaction = $serializer->deserialize(
            $request->getContent(),
            Transaction::class,
            'xml'
        );

        try {
            $transactionHandler->handleTransaction($transaction);
            $result = ['@status' => 'OK'];
        } catch (InsufficientFundsException $e) {
            $result = ['@status' => 'ERROR', '@msg' => 'insufficient funds'];
        } catch (\Throwable $e) {
            $result = ['@status' => 'ERROR', '@msg' => 'internal server error'];
        }

        $xml = $serializer->encode($result, 'xml', [
            XmlEncoder::ROOT_NODE_NAME => 'result'
        ]);

        return new Response($xml);
    }
}
