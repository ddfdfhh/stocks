<?php

namespace App\Models;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email','phone','country','state','city','pincode','address','image','status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // protected static function boot()
    // {
    //     parent::boot();
    //     User::creating(function ($model) {
            
    //             $model->password = Hash::make($model->password);
            

    //     });
    // }
    public function withCountry(){
        return $this->belongsTo(\App\Models\Country::class,'country','id')->withDefault();
    }
    public function withState(){
        return $this->belongsTo(\App\Models\Country::class,'country','id')->withDefault();
    }
    public function withCity(){
        return $this->belongsTo(\App\Models\Country::class,'country','id')->withDefault();
    }
    public function withPincode(){
        return $this->belongsTo(\App\Models\Country::class,'country','id')->withDefault();
    }
}
