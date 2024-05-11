<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $records    = Transaction::where('user_id','=',Auth::id())->latest()->paginate(10);
        $deposit    = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Deposit']])->sum('amount');
        $withdrawal = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Withdrawal']])->sum('amount');
        return view('dashboard',compact('records','deposit','withdrawal'));
    }

    public function deposit(): View
    {
        $records   = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Deposit']])->latest()->paginate(10);
        return view('depositForm',compact('records'));
    }

    public function depositAdd(Request $request): RedirectResponse
    {
        $validation = Validator::make($request->all(), [
            'deposit_balance' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $tras                   = new Transaction();
                $tras->user_id          = Auth::id();
                $tras->transaction_type = 'Deposit';
                $tras->amount           = $request->deposit_balance;
                $tras->fee              = 0.0;
                $tras->date             = date('Y-m-d');
                $tras->save();

                $user           = User::find(Auth::id());
                $user->balance  = $user->balance + $request->deposit_balance;
                $user->save();
            });

            return  Redirect::route('dashboard');
        } catch (\Exception $exception) {
            // dd($exception->getMessage());
            return Redirect::back()->withInput();
        }
    }

    public function withdrawal(): View
    {
        $records   = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Withdrawal']])->latest()->paginate(10);
        return view('withdrawalForm',compact('records'));
    }

    public function withdrawAdd(Request $request): RedirectResponse
    {
        $validation = Validator::make($request->all(), [
            'withdrawal_balance' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $fee = $this->calculateFee($request->withdrawal_balance) ?? 0;

                $tras                   = new Transaction();
                $tras->user_id          = Auth::id();
                $tras->transaction_type = 'Withdrawal';
                $tras->amount           = $request->withdrawal_balance;
                $tras->fee              = $fee;
                $tras->date             = date('Y-m-d');
                $tras->save();

                $user           = User::find(Auth::id());
                $user->balance  = $user->balance - $request->withdrawal_balance - $fee;
                $user->save();
            });

            return  Redirect::route('dashboard');
        } catch (\Exception $exception) {
            // dd($exception->getMessage());
            return Redirect::back()->withInput();
        }
    }

    public function calculateFee($balance)
    {
        if(Auth::user()->account_type == 'Individual'){
            $fee = $this->individualFee($balance);
        }else{
            $fee = $this->businessFee($balance);
        }

        return $fee;
    }

    public function individualFee($balance){
        $withdrawal = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Withdrawal']])->whereMonth('date','=',date('m'))->sum('amount');

        if(date('D') == 'Fri'){
            $fee = 0;
        }elseif(($withdrawal+$balance) <= 5000){
            $fee = 0;
        }elseif($balance > 1000){
            $fee = (($balance - 1000)*0.015)/100;
        }else{
            $fee = 0;
        }

        return $fee;
    }

    public function businessFee($balance){
        $withdrawal = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Withdrawal']])->sum('amount');

        if(($withdrawal+$balance) >= 50000){
            $fee = ($balance*0.015)/100;
        }else{
            $fee = ($balance*0.025)/100;
        }

        return $fee;
    }
}
