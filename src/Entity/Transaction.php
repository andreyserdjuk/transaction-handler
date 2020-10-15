<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ORM\Table(name="`transactions`")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $tid;

    /**
     * @ORM\Column(name="uid", type="string", length=255)
     */
    private string $uid;

    /**
     * @ORM\Column(type="bigint")
     */
    private int $amount;

    public function getTid()
    {
        return $this->tid;
    }

    public function setTid($tid)
    {
        $this->tid = $tid;
        return $this;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $uid): Transaction
    {
        $this->uid = $uid;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): Transaction
    {
        $this->amount = $amount;
        return $this;
    }
}
