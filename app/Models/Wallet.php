<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use AmrShawky\LaravelCurrency\Facade\Currency;
class Wallet extends Model
{
    protected $table = 'user_wallet';

    protected $fillable = ['user_id','currency','convert_currency','transfer_from','amount','transfer_date','conversion_amount'];


}
