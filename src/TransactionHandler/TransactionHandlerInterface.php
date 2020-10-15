<?php

namespace App\TransactionHandler;

use App\Entity\Transaction;

interface TransactionHandlerInterface
{
    public function handleTransaction(Transaction $transaction);
}
