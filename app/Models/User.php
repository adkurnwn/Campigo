<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'statususer',
        'alamat',
        'NIK',
        'no_hp',
        'tanggal_lahir',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isActive()
    {
        return $this->statususer === 'active';
    }

    public function isBanned()
    {
        return $this->statususer === 'banned';
    }

    public function isNonactive()
    {
        return $this->statususer === 'nonactive';
    }

    public function transaksiSewa()
    {
        return $this->hasMany(TransaksiSewa::class);
    }

     // Implement the getJWTIdentifier() method
     public function getJWTIdentifier()
     {
         return $this->getKey();
     }
 
     // Implement the getJWTCustomClaims() method
     public function getJWTCustomClaims()
     {
         return [];
     }
}
