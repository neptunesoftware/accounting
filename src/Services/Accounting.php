<?php

namespace NeptuneSoftware\Accounting\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use NeptuneSoftware\Accounting\Exceptions\TransactionCouldNotBeProcessed;
use NeptuneSoftware\Accounting\Models\Journal;
use Money\Money;
use Money\Currency;

use NeptuneSoftware\Accounting\Exceptions\InvalidJournalEntryValue;
use NeptuneSoftware\Accounting\Exceptions\InvalidJournalMethod;
use NeptuneSoftware\Accounting\Exceptions\DebitsAndCreditsDoNotEqual;
use NeptuneSoftware\Accounting\Interfaces\AccountingServiceInterface;

/**
 * Class Accounting
 * @package NeptuneSoftware\Accounting\Services
 */
class Accounting implements AccountingServiceInterface
{

    /**
     * @var array
     */
    protected $transctions_pending = [];

    /**
     * @return Accounting
     */
    public static function newDoubleEntryTransactionGroup(): AccountingServiceInterface
    {
        return new self;
    }

    /**
     * @param Journal $journal
     * @param string $method
     * @param Money $money
     * @param string|null $memo
     * @param null $referenced_object
     * @param Carbon|null $postdate
     * @throws InvalidJournalEntryValue
     * @throws InvalidJournalMethod
     * @internal param int $value
     */
    function addTransaction(Journal $journal, string $method, Money $money, string $memo = null, $referenced_object = null, Carbon $postdate = null)
    {

        if (!in_array($method, ['credit', 'debit'])) {
            throw new InvalidJournalMethod;
        }

        if ($money->getAmount() <= 0) {
            throw new InvalidJournalEntryValue();
        }

        $this->transctions_pending[] = [
            'journal' => $journal,
            'method' => $method,
            'money' => $money,
            'memo' => $memo,
            'referenced_object' => $referenced_object,
            'postdate' => $postdate
        ];
    }

    /**
     * @param Journal $journal
     * @param string $method
     * @param $value
     * @param string|null $memo
     * @param null $referenced_object
     * @param Carbon|null $postdate
     * @throws InvalidJournalEntryValue
     * @throws InvalidJournalMethod
     */
    function addDollarTransaction(Journal $journal, string $method, $value, string $memo = null, $referenced_object = null, Carbon $postdate = null)
    {
        $value = (int)($value * 100);
        $money = new Money($value, new Currency('USD'));
        $this->addTransaction($journal, $method, $money, $memo, $referenced_object, $postdate);
    }

    /**
     * @return array
     */
    function getTransactionsPending(): array
    {
        return $this->transctions_pending;
    }

    /**
     * @return string
     * @throws DebitsAndCreditsDoNotEqual
     * @throws TransactionCouldNotBeProcessed
     */
    public function commit(): string
    {
        $this->verifyTransactionCreditsEqualDebits();

        try {

            $transaction_group = \Ramsey\Uuid\Uuid::uuid4()->toString();

            DB::beginTransaction();

            foreach ($this->transctions_pending as $transction_pending) {
                $transaction = $transction_pending['journal']->{$transction_pending['method']}($transction_pending['money'], $transction_pending['memo'], $transction_pending['postdate'], $transaction_group);
                if ($object = $transction_pending['referenced_object']) {
                    $transaction->referencesObject($object);
                }
            }

            DB::commit();

            return $transaction_group;

        } catch (\Exception $e) {

            DB::rollBack();

            throw new TransactionCouldNotBeProcessed('Rolling Back Database. Message: ' . $e->getMessage());
        }
    }

    /**
     * @throws DebitsAndCreditsDoNotEqual
     */
    private function verifyTransactionCreditsEqualDebits()
    {
        $credits = 0;
        $debits = 0;

        foreach ($this->transctions_pending as $transction_pending) {
            if ($transction_pending['method'] == 'credit') {
                $credits += $transction_pending['money']->getAmount();
            } else {
                $debits += $transction_pending['money']->getAmount();
            }
        }
        if ($credits !== $debits) {
            throw new DebitsAndCreditsDoNotEqual('In this transaction, credits == ' . $credits . ' and debits == ' . $debits);
        }
    }
}