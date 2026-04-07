<?php

namespace App\Actions\Fortify;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): Siswa
    {
        Validator::make($input, [
            'nis' => [
                'required',
                'integer',
                Rule::unique(Siswa::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            return Siswa::create([
                'nis' => (int) $input['nis'],
                'kelas' => $input['kelas'] ?? '-',
                'password' => Hash::make($input['password']),
            ]);
        });
    }
}
