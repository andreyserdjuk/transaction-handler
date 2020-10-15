<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=32)
     */
    private string $uid;

    /**
     * @ORM\Column(type="bigint")
     */
    private int $ballance;

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $uid): Account
    {
        $this->uid = $uid;
        return $this;
    }

    public function getBallance(): int
    {
        return $this->ballance;
    }

    public function setBallance(int $ballance): Account
    {
        $this->ballance = $ballance;
        return $this;
    }
}
