<?php

namespace App\Tests\Functional;

class TransactionTest extends BaseTestCase
{
    private const SUCCESS = <<<"RESPONSE"
<?xml version="1.0"?>\n<result status="OK"/>\n
RESPONSE;

    private const NO_FUNDS = <<<"RESPONSE"
<?xml version="1.0"?>\n<result status="ERROR" msg="insufficient funds"/>\n
RESPONSE;

    private const ERROR = <<<"RESPONSE"
<?xml version="1.0"?>\n<result status="ERROR" msg="internal server error"/>\n
RESPONSE;

    /**
     * @dataProvider debitProvider
     */
    public function testSingleTransaction(
        string $operation,
        string $uid,
        string $tid,
        int $amount,
        string $expectedResponse
    )
    {
        $requestBody = sprintf(
            '<?xml version="1.0"?><operations><%s amount="%s" tid="%s" uid="%s"></%s></operations>',
            $operation,
            $amount,
            $tid,
            $uid,
            $operation
        );

        static::$client->request('POST', '/transaction', [], [], [], $requestBody);
        $rawResponse = self::$client->getResponse()->getContent();

        $this->assertEquals(
            $expectedResponse,
            $rawResponse
        );
    }

    public function testSuccessfulDebitAfterCredit()
    {
        $creditBody = '<?xml version="1.0"?>'
            . '<operations><credit amount="1000" tid="t-01" uid="account-1"></credit></operations>';
        $debitBody = '<?xml version="1.0"?>'
            . '<operations><debit amount="1000" tid="t-02" uid="account-1"></debit></operations>';

        static::$client->request('POST', '/transaction', [], [], [], $creditBody);
        static::$client->request('POST', '/transaction', [], [], [], $debitBody);
        $rawResponse = self::$client->getResponse()->getContent();

        $this->assertEquals(
            self::SUCCESS,
            $rawResponse
        );
    }

    public function debitProvider()
    {
        return [
            [
                'debit',            // operation
                'account-2',        // account id
                'transaction-2-3',  // transaction id
                10000,              // amount
                self::SUCCESS       // expected response
            ],
            [
                'debit',            // operation
                'account-2',        // account id
                'transaction-2-4',  // transaction id
                10001,              // amount
                self::NO_FUNDS      // expected response
            ],
            [
                'debit',            // operation
                'account-1',        // account id
                'transaction-1-4',  // transaction id
                1,                  // amount
                self::NO_FUNDS      // expected response
            ],
            [
                'debit',            // operation
                'account-2',        // account id
                'transaction-3',    // transaction id
                10000,              // amount
                self::ERROR         // expected response (transaction double)
            ],
            [
                'debit',            // operation
                'account-1',        // account id
                'transaction-3',    // transaction id
                10000,              // amount
                self::ERROR         // expected response (transaction double)
            ],
            [
                'credit',           // operation
                'account-1',        // account id
                'transaction-3',    // transaction id
                10000,              // amount
                self::ERROR         // expected response (transaction double)
            ],
        ];
    }
}
