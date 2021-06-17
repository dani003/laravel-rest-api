<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //POST /event {"type":"deposit", "destination":"100", "amount":10}
    //201 {"destination": {"id":"100", "balance":10}}

    public function store(Request $request)
    {
        if ($request->input('type') === 'deposit') {
            return $this->deposit(
                $request->input('destination'),
                $request->input('amount')
            );
        } elseif ($request->input('type') === 'withdraw') {
            return $this->withdraw(
                $request->input('origin'),
                $request->input('amount')
            );
        }
    }

    private function deposit($destination, $amount)
    {
        $account = Account::firstOrCreate([
            'id' => $destination
        ]);

        $account->balance += $amount;
        $account->save(); //UPDATE

        return response()->json([
            'destination' => [
                'id' => $account->id,
                'balance' => $account->balance
            ]
        ], 201);
    }

    // Withdraw from non-existing account
    //POST /event {"type":"withdraw", "origin":"200", "amount":10}
    //404 0

    private function withdraw($origin, $amount)
    {
        $account = Account::findOrFail([
            'id' => $origin
        ]);
    }
}
