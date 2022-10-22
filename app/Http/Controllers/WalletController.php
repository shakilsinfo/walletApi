<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use DB;
use App\ApiResource\WalletDataResource;
class WalletController extends Controller
{
    private WalletDataResource $walletRepository;
    function __construct(WalletDataResource $walletRepository){
        $this->walletResource = $walletRepository;
    }

    public function saveWalletData(Request $request){

        $this->validate($request, [
            'user_id' => 'required|integer',
            'currency' => 'required',
            'amount' => 'required'
        ]);

        return $this->walletResource->createWallet($request->all());
        

        
    }
    public function userList(){
        $getUser = User::where('id',"<>",auth()->user()->id)->get();
        return $getUser;
    }
    public function getTransactionList(){
        return $this->walletResource->walletList();  
    }

    public function highestTransaction(){
        $userName = "'".auth()->user()->name."'";
        
        $thirdHighestSalary = DB::select("SELECT * FROM user_wallet wallet WHERE transfer_from =$userName AND ( 3 ) = ( SELECT COUNT( postWallet.conversion_amount ) FROM user_wallet postWallet WHERE postWallet.conversion_amount >= wallet.conversion_amount
                 )");
        return $thirdHighestSalary;
    }
    
}
