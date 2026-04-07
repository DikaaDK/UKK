<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['nis', 'kelas', 'password'])]
#[Hidden(['password', 'remember_token'])]
class Siswa extends Authenticatable
{
    /**
     * The table associated with the model.
     */
    protected $table = 'siswa';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'nis';

    /**
     * The primary key is not auto-incrementing because we assign it manually.
     */
    public $incrementing = false;

    /**
     * The primary key type.
     */
    protected $keyType = 'int';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
