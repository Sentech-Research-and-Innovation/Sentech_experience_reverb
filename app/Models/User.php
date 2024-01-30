<?php

namespace App\Models;

use App\Models\Company\Company;
use App\Models\Company\CompanyStaff;
use App\Models\Company\CompanyUser;
use App\Models\LoanWizard\PersonalDetail;
use App\Models\UserModels\UserCompany;
use App\Models\UserModels\UserPermissions;
use App\Models\UserModels\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    // public function role()
    // {
    //     return self::HasOne(UserRole::class, 'user_id', 'id');
    // }

    // public function permissions()
    // {
    //     return self::HasMany(UserPermissions::class, 'user_id', 'id');
    // }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $front_url = config('app.url');
        $url = $front_url . '/change_password' . '/token?=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
