<?php

namespace App\Models;

use App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

use App\Notifications\ResetPasswordNotification;



class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use LaravelPermissionToVueJS;

    //protected $guard_name = "api";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'company_id'
    // 

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $email = $this->email;
        $front_url = config('app.url');
        $url = $front_url . '/change_password' . '/token?=' . $token . '&email=' . $email;

        $this->notify(new ResetPasswordNotification($url));
    }

    // // In your User model (app/Models/User.php)
    // public function getProfilePhotoUrlAttribute()
    // {
    //     if (!$this->profile_photo_path) {
    //         return null;
    //     }
        
    //     // Generate the full URL to the image
    //     // return Storage::disk('public')->url($this->profile_photo_path);
        
    //     // OR if you prefer using the asset helper:
    //     return asset('storage/' . $this->profile_photo_path);
    // }
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->profile_photo_path) {
            return null;
        }
        
        return \Storage::disk('public')->url($this->profile_photo_path);
    }

}
