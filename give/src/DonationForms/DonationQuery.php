<?php

namespace Give\DonationForms;

use Give\Donations\ValueObjects\DonationMetaKeys;
use Give\Framework\QueryBuilder\JoinQueryBuilder;
use Give\Framework\QueryBuilder\QueryBuilder;

/**
 * An opinionated Query Builder for GiveWP donations and meta fields.
 *
 * @since 3.12.0
 *
 * Example usage:
 * (new DonationQuery)
 *     ->form(1816)
 *     ->between('2024-02-00', '2024-02-23')
 *     ->sumIntendedAmount();
 */
class DonationQuery extends QueryBuilder
{
    /**
     * @since 3.12.0
     */
    public function __construct()
    {
        $this->from('posts', 'donation');
    }

    /**
     * An opinionated join method for the donation meta table.
     * @since 3.12.0
     */
    public function joinMeta($key, $alias): DonationQuery
    {
        $this->join(function (JoinQueryBuilder $builder) use ($key, $alias) {
            $builder
                ->leftJoin('give_donationmeta', $alias)
                ->on('donation.ID', $alias . '.donation_id')
                ->andOn($alias . '.meta_key', $key, true);
        });
        return $this;
    }

    /**
     * An opinionated where method for the donation form ID meta field.
     * @since 3.12.0
     */
    public function form($formId)
    {
        $this->joinMeta('_give_payment_form_id', 'formId');
        $this->where('formId.meta_value', $formId);
        return $this;
    }


    /**
     * An opinionated where method for the multiple donation form IDs meta field.
     * @since 3.12.0
     */
    public function forms(array $formIds): DonationQuery
    {
        $this->joinMeta('_give_payment_form_id', 'formId');
        $this->whereIn('formId.meta_value', $formIds);
        return $this;
    }

    /**
     * An opinionated whereBetween method for the completed date meta field.
     * @since 3.12.0
     */
    public function between($startDate, $endDate): DonationQuery
    {
        // If the dates are empty or invalid, they will fallback to January 1st, 1970.
        // For the start date, this is exactly what we need, but for the end date, we should set it as the current date so that we have a correct date range.
        $startDate = date('Y-m-d H:i:s', strtotime($startDate));
        $endDate = empty($endDate)
            ? date('Y-m-d H:i:s')
            : date('Y-m-d H:i:s', strtotime($endDate));

        $this->whereBetween('donation.post_date', $startDate, $endDate);
        return $this;
    }

    /**
     * @since 3.14.0
     */
    public function includeOnlyValidStatuses(): DonationQuery
    {
        $this->whereIn('donation.post_status', ['publish', 'give_subscription']);

        return $this;
    }

    /**
     * @since 3.14.0
     */
    public function includeOnlyCurrentMode(): DonationQuery
    {
        $this->joinMeta('_give_payment_mode', 'paymentMode');
        $this->where('paymentMode.meta_value', give_is_test_mode() ? 'test' : 'live');

        return $this;
    }

    /**
     * Returns a calculated sum of the intended amounts (without recovered fees) for the donations.
     *
     * @since 4.5.0 update to account for exchange rate
     * @since 3.14.0 Use the NULLIF function to prevent zero values that can generate a wrong final result and use $this->includeOnlyValidStatuses() and $this->includeOnlyCurrentMode()
     * @since 3.12.0
     * @return int|float
     */
    public function sumIntendedAmount($includeOnlyValidStatuses = true, $includeOnlyCurrentMode = true)
    {
        if ($includeOnlyValidStatuses) {
            $this->includeOnlyValidStatuses();
        }

        if ($includeOnlyCurrentMode) {
            $this->includeOnlyCurrentMode();
        }

        $this->joinMeta(DonationMetaKeys::AMOUNT, 'amount');
        $this->joinMeta(DonationMetaKeys::FEE_AMOUNT_RECOVERED, 'feeAmountRecovered');
        $this->joinMeta(DonationMetaKeys::EXCHANGE_RATE, 'exchangeRate');
        return $this->sum(
            '(IFNULL(amount.meta_value, 0) - IFNULL(feeAmountRecovered.meta_value, 0)) / IFNULL(exchangeRate.meta_value, 1)'
        );
    }

    /**
     * Returns a calculated sum of the amounts (with recovered fees) for the donations.
     *
     * @since 3.14.0
     * @return int|float
     */
    public function sumAmount($includeOnlyValidStatuses = true, $includeOnlyCurrentMode = true)
    {
        if ($includeOnlyValidStatuses) {
            $this->includeOnlyValidStatuses();
        }

        if ($includeOnlyCurrentMode) {
            $this->includeOnlyCurrentMode();
        }

        $this->joinMeta('_give_payment_total', 'amount');

        return $this->sum(
            'amount.meta_value'
        );
    }

    public function countDonors()
    {
        $this->joinMeta(DonationMetaKeys::DONOR_ID, 'donorId');
        return $this->count('DISTINCT donorId.meta_value');
    }
}
