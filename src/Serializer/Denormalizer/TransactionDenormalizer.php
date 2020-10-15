<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Transaction;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TransactionDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (count($data) > 1) {
            throw new InvalidArgumentException('Transaction can contain only one operation at time.');
        }

        if (isset($data['debit'])) {
            $sign = -1;
            $operationData = $data['debit'];
        } elseif (isset($data['credit'])) {
            $sign = 1;
            $operationData = $data['credit'];
        } else {
            throw new UnexpectedValueException('Unsupported transaction type.');
        }

        if (!isset($operationData['@amount']) ||
            !isset($operationData['@tid']) ||
            !isset($operationData['@uid'])
        ) {
            throw new UnexpectedValueException('Inconsistent transaction data.');
        }

        return (new Transaction())
            ->setAmount($sign * ((int) $operationData['@amount']))
            ->setTid((string) $operationData['@tid'])
            ->setUid((string) $operationData['@uid']);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return Transaction::class === $type;
    }
}
