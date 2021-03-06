<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;

class Notification extends Model
{
    protected  $table = "notifications";

    protected $appends = ['crt_human', 'sender_uname'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target', 'sender', 'value', 'active',
    ];

    /**
     * Get the user record associated with the notif.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'target', 'id');
    }

    public function getCrtHumanAttribute()
    {
        $crtat = $this->attributes['created_at'];

        return Carbon::createFromFormat('Y-m-d H:i:s', $crtat)->diffForHumans();        
    }

    public function getSenderUnameAttribute()
    {
        $id = $this->attributes['senderid'];
        $user = User::find($id);

        if ($user) {
            return $user->username;
        } else {
            return "showdefault";
        }        
    }
}
