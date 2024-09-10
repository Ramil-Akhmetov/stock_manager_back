<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogActivity;

    protected $fillable = ['name', 'surname', 'patronymic', 'email', 'phone', 'password', 'photo'];

    //TODO maybe delete email_verified_at from hidden
    //TODO should think when user must verify email
    protected $hidden = ['password', 'remember_token', 'deleted_at'];
    protected $with = ['roles'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'extra_attributes' => SchemalessAttributes::class,
    ];

    // TODO do smth
    // protected $with = ['roles:id,name', 'checkins'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logExcept(['password'])
            ->logOnlyDirty();
    }

    //region Relationships
    public function responsibilities()
    {
        return $this->hasMany(Responsibility::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function room()
    {
        return $this->hasOne(Room::class);
    }

    public function confirmations()
    {
        return $this->hasMany(Confirmation::class);
    }

    public function invite_code()
    {
        return $this->hasOne(InviteCode::class);
    }

    //endregion

    public function scopeWithExtraAttributes()
    {
        return $this->extra_attributes->modelScope();
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search']) {
            $query->search($filters['search']);
        }
    }

    public function scopeSearch($query, $s)
    {
        $query->where('name', 'like', "%$s%")
            ->orWhere('surname', 'like', "%$s%")
            ->orWhere('patronymic', 'like', "%$s%")
            ->orWhere('phone', 'like', "%$s%")
            ->orWhere('email', 'like', "%$s%");
    }

    //TODO
    //    public function sendPasswordResetNotification($token)
    //    {
    //        $url = env('FRONTEND_URL', 'http://localhost:3000') . '/reset-password/' . $token;
    //        $this->notify(new ResetPasswordNotification($url));
    //    }
}
