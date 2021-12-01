<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\IsValidPhone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return User
     */
    public function create(array $input): User
    {
        Validator::validate($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'nullable', 'string', 'max:20', Rule::unique('users', 'phone')->whereNull('deleted_at'),
                new IsValidPhone
            ],
            'email' => ['required', 'email'],
            'password' => $this->passwordRules()
        ], customAttributes: [
            'name' => 'Nama',
            'phone' => 'Nomor Telepon',
            'email' => 'Email',
            'password' => 'Kata Sandi'
        ]);
        return User::create([
            'name' => $input['name'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'])
        ]);
    }
}
