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
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    public function owners()
    {
        $response = [];
        $owners = UserRole::where('role_id', 2)->get();
        if (count($owners)) {
            foreach ($owners as $key => $owner) {
                if (!is_null($owner->owner))
                    $response[] = $owner->owner;
            }
        } else {
            $response = false;
        }

        //        dd($response);
        return $response;
    }

    public function staff()
    {
        $response = [];
        $owners = UserRole::where('role_id', '!=', 2)->get();
        if (count($owners)) {
            foreach ($owners as $key => $owner) {
                if (!is_null($owner->owner))
                    $response[] = $owner->owner;
            }
        } else {
            $response = false;
        }

        return $response;
    }

    public function assignedOwners($id)
    {
        $response = [];
        $companyUsers = UserCompany::where('company_id', $id)->get();
        if (count($companyUsers)) {
            foreach ($companyUsers as $key => $value) {
                if ($value->role->role_id == 2) {
                    if (!is_null($value->owner))
                        $response[] = $value->owner;
                }
            }
        } else {
            $response = [];
        }
        //        dd($response);
        return $response;
    }
    public function assignedStaff($id)
    {
        $response = [];
        $companyUsers = CompanyStaff::where('branch_id', $id)->get();
        if (count($companyUsers)) {
            foreach ($companyUsers as $key => $value) {
                if (!is_null($value->staff))
                    $response[] = $value->staff;
            }
        } else {
            $response = [];
        }
        //        dd($response);
        return $response;
    }
    public function role()
    {
        return self::HasOne(UserRole::class, 'user_id', 'id');
    }

    public function permissions()
    {
        return self::HasMany(UserPermissions::class, 'user_id', 'id');
    }
}
