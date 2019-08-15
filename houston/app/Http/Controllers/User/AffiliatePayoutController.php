<?php

namespace App\Http\Controllers\User;

use App\Affiliate;
use App\AffiliatePayout;
use App\User;
use Illuminate\Http\Request;

class AffiliatePayoutController extends Affiliate
{
    public function index(Request $request)
    {
        $page = 'payment';
        $user = $request->user();
        $user_id = $user->id;
        $payout_minimum = config('payout_minimum');
        $payout_check = 0;
        $current_account_balance = $this->trimTrailingZeroes($user->current_account_balance);
        if ($current_account_balance >= $payout_minimum) {
            $payout_check = 1;
        }

        $affiliate_payouts = new AffiliatePayout();
        $history_payouts = $affiliate_payouts->where('user_id', $user_id)->get()->toArray();

        $user = new User();
        $paypal = $user->where('id', $user_id)->first()->paypal_email;
        $payza = $user->where('id', $user_id)->first()->payza_email;

        return view('affiliate.payment', compact(
            'page',
            'user',
            'payout_check',
            'current_account_balance',
            'payout_minimum',
            'paypal',
            'history_payouts',
            'payza'
        ));
    }

    public function payMe(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $current_account_balance = $user->current_account_balance;
        $pay_out = new AffiliatePayout();
        $pay_out->create([
            'user_id'          => $user_id,
            'requested_amount' => $current_account_balance,
            'ip'               => $request->ip(),
        ]);

        $user->update(['current_account_balance' => 0, 'current_referral_balance' => 0]);
        flash('You have requested your payout!', 'success');
    }

    public function updateEmail(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $paypal = $request->input('paypal');
        $payza = $request->input('payza');

        $user = new User();
        $user->where('id', $user_id)->update([
            'paypal_email' => $paypal,
            'payza_email'  => $payza,
        ]);

        flash('Your payment email address has been updated!', 'success');
    }

    public function trimTrailingZeroes($str)
    {
        return preg_replace('/(\.[0-9]+?)0*$/', '$1', number_format($str, 10));
    }
}
