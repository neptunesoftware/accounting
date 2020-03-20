<?php

namespace NeptuneSoftware\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Money\Money;
use Money\Currency;
use Carbon\Carbon;

/**
 * Class Journal
 * @package NeptuneSoftware\AccountingService
 * @property    Money                  $balance
 * @property    string                 $currency
 * @property    Carbon                 $updated_at
 * @property    Carbon                 $post_date
 * @property    Carbon                 $created_at
 */
class Ledger extends Model
{
	
	/**
	 * @var string
	 */
	protected $table = 'accounting_ledgers';
	
	public $currency = 'USD';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            /**
             * @var \Illuminate\Database\Eloquent\Model $model
             */
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
	
	/**
	 *
	 */
	public function journals()
	{
		return $this->hasMany(Journal::class);
	}
	
	/**
     * Get all of the posts for the country.
     */
    public function journal_transctions()
    {
        return $this->hasManyThrough(JournalTransaction::class, Journal::class);
    }
	
	/**
	 *
	 */
	public function getCurrentBalance()
	{
		if ($this->type == 'asset' || $this->type == 'expense') {
			$balance = $this->journal_transctions->sum('debit') - $this->journal_transctions->sum('credit');
		} else {
			$balance = $this->journal_transctions->sum('credit') - $this->journal_transctions->sum('debit');
		}
		
		return new Money($balance, new Currency($this->currency));
	}
	
		/**
	 *
	 */
	public function getCurrentBalanceInDollars()
	{
		return $this->getCurrentBalance()->getAmount() / 100;
	}
	
	
}