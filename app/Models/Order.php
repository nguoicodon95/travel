<?php

namespace App\Models;

use \Carbon\Carbon;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'qty',
        'amount',
        'transaction_id',
        'product_id',
        'data',
        'status'
    ];

   public function transaction()
   {
       return $this->belongsTo('App\Models\Transaction', 'transaction_id');
   }

    public function products() {
    	return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function getCreatedAtAttribute( $value ) {
    	return Carbon::parse($value)->format('d/m/Y H:i');
    }
}
