<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Services\PaymentService;
use App\Services\PricingService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FrontController extends Controller
{
    protected $transactionService;
    protected $paymentService;
    protected $pricingService;

    public function __construct(TransactionService $transactionService, PaymentService $paymentService, PricingService $pricingService)
    {
        $this->transactionService = $transactionService;
        $this->paymentService = $paymentService;
        $this->pricingService = $pricingService;
    }

    public function index()
    {
        return view('welcome');
    }

    public function pricing()
    {
        $pricingPackages = $this->pricingService->getAllPackages();
        $user = Auth::user();

        // return to pricing
    }

    public function checkout(Pricing $pricing)
    {
        $checkoutData = $this->transactionService->prepareCheckout($pricing);

        if ($checkoutData['alreadySubscribed']) {
            // redirect to pricing
        }

        // return to checkout
    }

    public function checkoutSuccess()
    {
        $pricing = $this->transactionService->getRecentPricing();

        if (!$pricing) {
            //  redirect to pricing with error
        }

        // return to checkout success
    }

    public function paymentStoreMidtrans()
    {
        try {
            $pricingId = session()->get('pricing_id');

            if (!$pricingId) {
                return response()->json(['error' => 'No pricing data found in the session.'], 400);
            }

            $snapToken = $this->paymentService->createPayment($pricingId);

            if (!$snapToken) {
                return response()->json(['error' => 'Failed to create Midtrans transaction.'], 500);
            }

            return response()->json(['snap_token' => $snapToken], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }

    public function paymentMidtransNotification()
    {
        try {
            $transactionStatus = $this->paymentService->handlePaymentNotification();

            if (!$transactionStatus) {
                return response()->json(['error' => 'Invalid notification data.'], 400);
            }

            return response()->json(['status' => $transactionStatus], 200);
        } catch (\Exception $e) {
            Log::error('Failed handle Midtrans notification: ', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Failed to process notification'], 500);
        }
    }
}
