<?php
namespace App\ApiResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\User;
use DB;
use AmrShawky\LaravelCurrency\Facade\Currency;
class WalletDataResource{

	public function createWallet($requestData){

		$saveData = array();
        
        $validateUser = User::where('id',$requestData['user_id'])->first();
        if(!empty($validateUser)){
        	$saveData['user_id'] = $requestData['user_id'];
        	$saveData['currency'] = $requestData['currency'];
         	$saveData['convert_currency'] = $validateUser->currency;
         	   
         	$amountConversion = Currency::convert()
                        ->from($requestData['currency'])
                        ->to($validateUser->currency)
                        ->amount($requestData['amount'])
                        ->get();
            $saveData['transfer_from'] = auth()->user()->name;
            $saveData['amount'] = $requestData['amount'];
            $saveData['conversion_amount'] = $amountConversion;
            $saveData['transfer_date'] = date('Y-m-d');
            try {
            	$bug = 0; 
            	
            	$user = Wallet::create($saveData);
            	
	            
            } catch (Exception $e) {
            	$bug = $e->getMessage();
            }
            if($bug == 0){
            	return [
		            'status' => '200',
		            'message' => 'Wallet transfer successfully to '.$validateUser->name,
		            'error' => 'false'
		        ];
            }else{
            	return [
		            'status' => '401',
		            'message' => $bug,
		            'error' => 'true'
		        ];
            }
        }else{
           return [
                'status' => 400,
                'message' => 'User is invalid',
                'error' => 'true'
            ];
        }
        
        
	}

	public function walletList(){

		$getTransactionList = Wallet::join('users','user_wallet.user_id','users.id')->select('user_wallet.*','users.name as user_name')->where('user_wallet.transfer_from', auth()->user()->name)->orderBy('user_wallet.transfer_date', 'asc')->get();
		if(!empty($getTransactionList)){
			return [
				'status' => 200,
				'message' => 'Data retrieve successfully',
				'error' => false,
				'data' => json_decode(json_encode($getTransactionList),true)
			];
		}else{
			return [
				'status' => 201,
				'message' => 'No data found',
				'error' => false,
				'data' => []
			];
		}
	}
}