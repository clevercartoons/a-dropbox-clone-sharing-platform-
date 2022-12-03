<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PaymentGateway;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Validator;

class CheckoutController extends Controller
{
    public function index($checkout_id)
    {
        $transaction = Transaction::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id], ['status', 0]])->with('plan')->firstOrFail();
        $paymentGateways = PaymentGateway::where('status', 1)->get();
        return view('frontend.user.checkout.index', [
            'user' => userAuthInfo(),
            'transaction' => $transaction,
            'paymentGateways' => $paymentGateways,
        ]);
    }

    public function proccess(Request $request, $checkout_id)
    {
        $validator = Validator::make($request->all(), [
            'address_1' => ['required', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:150'],
            'state' => ['required', 'string', 'max:150'],
            'zip' => ['required', 'string', 'max:100'],
            'country' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $transaction = Transaction::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id], ['status', 0]])->first();
        if (is_null($transaction)) {
            toastr()->error(lang('Invalid or expired transaction', 'checkout'));
            return back();
        }
        if ($transaction->total_price != 0) {
            $paymentGateway = PaymentGateway::where([['id', unhashid($request->payment_method)], ['status', 1]])->first();
            if (is_null($paymentGateway)) {
                toastr()->error(lang('Selected payment method is not active', 'checkout'));
                return back();
            }
        }
        $country = Country::find($request->country);
        if (is_null($country)) {
            toastr()->error(lang('Country not exists', 'alerts'));
            return back();
        }
        $address = [
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $country->name,
        ];
        $user = Auth::user();
        $updateUserAddress = $user->update(['address' => $address]);
        if ($transaction->total_price == 0) {
            $transaction->update(['status' => 1]);
            $this->updateSubscription($transaction);
            toastr()->success(lang('Subscribed Successfully', 'alerts'));
            return redirect()->route('user.subscription');
        }

        $paymentHandler = $paymentGateway->handler;
        $paymentData = $paymentHandler::process($transaction);
        $paymentData = json_decode($paymentData);

        if ($paymentData->error == true) {
            toastr()->error($paymentData->msg);
            return back();
        }
        return redirect($paymentData->redirectUrl);
    }

    public static function updateSubscription($trx)
    {
        if ($trx->status != 1) {
            throw new Exception(lang('Incomplete payment please open a ticket or contact us', 'checkout'));
        }

        if (!is_null(userAuthInfo()->subscription) && !userAuthInfo()->subscription->status) {
            throw new Exception(lang('Your subscription has been canceled, please contact us for more information', 'alerts'));
        }

        if ($trx->type == 0) {
            $expiry_at = ($trx->plan->interval) ? Carbon::now()->addYear() : Carbon::now()->addMonth();
            $createSubscription = Subscription::create([
                'user_id' => $trx->user_id,
                'plan_id' => $trx->plan_id,
                'expiry_at' => $expiry_at,
            ]);
            if ($createSubscription) {
                $title = userAuthInfo()->name . ' has subscribed';
                $image = asset('images/icons/subscribe.png');
                $link = route('admin.users.edit', $createSubscription->user_id);
                adminNotify($title, $image, $link);
            }
        }

        if ($trx->type == 1) {
            if ($trx->plan->interval) {
                if (subscription()->remining_days < 0) {
                    $expiry_at = Carbon::now()->addYear();
                } else {
                    $expiry_at = Carbon::parse(subscription()->expired_date)->addYear();
                }
            } else {
                if (subscription()->remining_days < 0) {
                    $expiry_at = Carbon::now()->addMonth();
                } else {
                    $expiry_at = Carbon::parse(subscription()->expired_date)->addMonth();
                }
            }
            $updateSubscription = Subscription::where('user_id', $trx->user_id)->update(['expiry_at' => $expiry_at]);
            if ($updateSubscription) {
                $title = userAuthInfo()->name . ' has renewed subscription';
                $image = asset('images/icons/renewal.png');
                $link = route('admin.users.edit', $trx->user_id);
                adminNotify($title, $image, $link);
            }
        }

        if ($trx->type == 2) {
            $expiry_at = ($trx->plan->interval) ? Carbon::now()->addYear() : Carbon::now()->addMonth();
            $updateSubscription = Subscription::where('user_id', $trx->user_id)->update([
                'plan_id' => $trx->plan_id,
                'expiry_at' => $expiry_at,
            ]);
            if ($updateSubscription) {
                $title = userAuthInfo()->name . ' has upgrade subscription';
                $image = asset('images/icons/upgrade.png');
                $link = route('admin.users.edit', $trx->user_id);
                adminNotify($title, $image, $link);
            }
        }
    }
}
