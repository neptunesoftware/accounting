<?php


namespace Scottlaurent\Accounting\Interfaces;


use Carbon\Carbon;
use Money\Money;
use Scottlaurent\Accounting\Exceptions\DebitsAndCreditsDoNotEqual;
use Scottlaurent\Accounting\Exceptions\InvalidJournalEntryValue;
use Scottlaurent\Accounting\Exceptions\InvalidJournalMethod;
use Scottlaurent\Accounting\Models\Journal;

interface AccountingServiceInterface
{
    /**
     * @return AccountingServiceInterface
     */
    public static function newDoubleEntryTransactionGroup(): self;

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
    public function addTransaction(Journal $journal, string $method, Money $money, string $memo = null, $referenced_object = null, Carbon $postdate = null);

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
    public function addDollarTransaction(Journal $journal, string $method, $value, string $memo = null, $referenced_object = null, Carbon $postdate = null);

    /**
     * @return array
     */
    function getTransactionsPending(): array;

    /**
     * @return string
     * @throws DebitsAndCreditsDoNotEqual
     */
    public function commit(): string;
}