<?php

namespace App\TransactionHandler;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use App\TransactionHandler\Exception\InsufficientFundsException;
use App\TransactionHandler\Exception\TransactionException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

class TransactionHandler
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws TransactionException
     * @throws \Doctrine\ORM\ORMException
     */
    public function handleTransaction(Transaction $transaction)
    {
        $uid = $transaction->getUid();
        $account = $this->em->getReference(Account::class, $uid);
        $this->em->beginTransaction();
        $this->em->persist($transaction);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->rollback();
            throw new TransactionException('Repeating transaction.');
        }

        $amount = $transaction->getAmount();
        /** @var AccountRepository $accountRepo */
        $accountRepo = $this->em->getRepository(Account::class);

        if ($amount > 0) {
            $accountRepo->incrementBallance($uid, $amount);
            $this->em->commit();
        } elseif ($amount < 0) {
            $account = $accountRepo->find($uid, LockMode::PESSIMISTIC_WRITE);
            $expectedAmount = $account->getBallance() + $amount;
            if ($expectedAmount >= 0) {
                $account->setBallance($expectedAmount);
                $this->em->flush();
                $this->em->commit();
            } else {
                throw new InsufficientFundsException();
            }
        } else {
            $this->em->rollback();
        }
    }
}
