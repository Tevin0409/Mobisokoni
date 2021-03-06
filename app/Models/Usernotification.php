<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usernotification extends Model
{
    use HasFactory;
    public $table = "usernotification";

    public function order()
    {
        return $this->hasOne('App\Models\Order', "id",'order_id');
    }
}
