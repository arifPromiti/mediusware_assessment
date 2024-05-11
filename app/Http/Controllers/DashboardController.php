<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;

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
        $records   = Transaction::where('user_id','=',Auth::id())->latest()->paginate(10);
        $deposit    = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Deposit']])->sum('amount');
        $withdrawal = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Withdrawal']])->sum('amount');
        return view('dashboard',compact('records','deposit','withdrawal'));
    }

    public function deposit(): View
    {
        $records   = Transaction::where([['user_id','=',Auth::id()],['transaction_type','=','Deposit']])->latest()->paginate(10);
        return view('depositForm',compact('records'));
    }

    public function depositAdd(Request $request)
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
        return view('depositForm',compact('records'));
    }
}
