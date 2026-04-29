<?php

namespace App\Services;

use App\Models\Pricing;
use App\Models\Transaction;
use App\Repositories\PricingRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    protected $pricingRepository;
    protected $transactionRepository;

    public function __construct(PricingRepositoryInterface $pricingRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->pricingRepository = $pricingRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function prepareCheckout(Pricing $pricing)
    {
        $user = Auth::user();
        $alreadySubscribed = $pricing->isSubscribedByUser($user->id);

        $tax = 0.11;
        $totalTaxAmount = $pricing->price * $tax;
        $subTotalAmount = $pricing->price;
        $grandTotalAmount = $subTotalAmount + $totalTaxAmount;

        $startedAt = now();
        $endedAt = $startedAt->copy()->addMonths($pricing->duration);

        session()->put('pricing_id', $pricing->id);

        return compact(
            'user',
            'alreadySubscribed',
            'pricing',
            'totalTaxAmount',
            'subTotalAmount',
            'grandTotalAmount',
            'startedAt',
            'endedAt'
        );
    }

    public function getRecentPricing()
    {
        $pricingId = session()->get('pricing_id');

        return $this->pricingRepository->findById($pricingId);
    }

    public function getUserTransactions()
    {
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        return $this->transactionRepository->getUserTransaction($user->id);
    }
}
