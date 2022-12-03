<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unreadSubscriptions = Subscription::where('read_status', 0)->get();
        if (count($unreadSubscriptions) > 0) {
            foreach ($unreadSubscriptions as $unreadSubscription) {
                $unreadSubscription->read_status = 1;
                $unreadSubscription->save();
            }
        }
        $users = User::where('status', 1)->with('subscription')->get();
        $plans = Plan::all();
        $activeSubscriptions = Subscription::where([['status', 1], ['expiry_at', '>', Carbon::now()]])->with(['user', 'plan'])->get();
        $expiredSubscriptions = Subscription::where([['status', 1], ['expiry_at', '<', Carbon::now()]])->with(['user', 'plan'])->get();
        $canceledSubscriptions = Subscription::where('status', 0)->with(['user', 'plan'])->get();
        return view('backend.subscriptions.index', [
            'users' => $users,
            'plans' => $plans,
            'activeSubscriptions' => $activeSubscriptions,
            'expiredSubscriptions' => $expiredSubscriptions,
            'canceledSubscriptions' => $canceledSubscriptions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => ['required', 'integer'],
            'plan' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $user = User::where('id', $request->user)->with('subscription')->first();
        if (is_null($user)) {
            toastr()->error(__('User not exists'));
            return back();
        }
        if (!is_null($user->subscription)) {
            toastr()->error(__('User already subscribed'));
            return back();
        }
        $plan = Plan::find($request->plan);
        if (is_null($plan)) {
            toastr()->error(__('Plan not exists'));
            return back();
        }
        $expiry_at = ($plan->interval) ? Carbon::now()->addYear() : Carbon::now()->addMonth();
        $createSubscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'expiry_at' => $expiry_at,
            'read_status' => 1,
        ]);
        if ($createSubscription) {
            toastr()->success(__('Created successfully'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $data['plan'] = $subscription->plan_id;
        $data['expiry_at'] = Carbon::parse($subscription->expiry_at)->format('Y-m-d\TH:i:s');
        $data['status'] = $subscription->status;
        $data['update_link'] = route('admin.subscriptions.update', $subscription->id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'boolean'],
            'plan' => ['required', 'integer'],
            'expiry_at' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $plan = Plan::find($request->plan);
        if (is_null($plan)) {
            toastr()->error(__('Plan not exists'));
            return back();
        }
        $expiry_at = Carbon::parse($request->expiry_at);
        $updateSubscription = $subscription->update([
            'plan_id' => $plan->id,
            'expiry_at' => $expiry_at,
            'status' => $request->status,
        ]);
        if ($updateSubscription) {
            toastr()->success(__('Updated successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $transfers = Transfer::where('user_id', $subscription->user_id)->with('transferFiles')->get();
        if ($transfers->count() > 0) {
            foreach ($transfers as $transfer) {
                foreach ($transfer->transferFiles as $transferFile) {
                    $handler = $transferFile->storageProvider->handler;
                    $handler::delete($transferFile->path);
                }
                $transfer->delete();
            }
        }
        $transactions = Transaction::where('user_id', $subscription->user_id)->get();
        if ($transactions->count() > 0) {
            foreach ($transactions as $transaction) {
                $transaction->delete();
            }
        }
        $subscription->delete();
        toastr()->success(__('Deleted successfully'));
        return back();
    }
}
