<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    public function subscription()
    {
        $transactions = $this->transactionService->getUserTransactions();

        // return to subscription
    }
    
    public function subscriptionDetail(Transaction $transaction)
    {
        // return to subscription detail
    }
}
