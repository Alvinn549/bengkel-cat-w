<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class MidtransController extends Controller
{
    private function setupMidtrans()
    {
        MidtransConfig::$serverKey = config('services.midtrans.serverKey');
        MidtransConfig::$isProduction = config('services.midtrans.isProduction');
        MidtransConfig::$isSanitized = config('services.midtrans.isSanitized');
        MidtransConfig::$is3ds = config('services.midtrans.is3ds');
    }

    public function getSnapToken(Request $request)
    {
        $this->setupMidtrans();
        $input = $request->all();
        $snapToken = MidtransSnap::getSnapToken($input);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }
}
