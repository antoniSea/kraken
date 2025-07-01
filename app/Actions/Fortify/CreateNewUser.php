<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:255'],
            'apartment' => ['nullable', 'string', 'max:255'],
            'consent_personal_data' => ['accepted'],
            'consent_email' => ['accepted'],
            'consent_marketing' => ['nullable', 'boolean'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $password = Str::random(12);

        $user = User::create([
            'name' => $input['name'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone' => $input['phone'] ?? null,
            'city' => $input['city'] ?? null,
            'apartment' => $input['apartment'] ?? null,
            'consent_personal_data' => $input['consent_personal_data'] ?? false,
            'consent_email' => $input['consent_email'] ?? false,
            'consent_marketing' => $input['consent_marketing'] ?? false,
            'email' => $input['email'],
            'password' => Hash::make($password),
        ]);

        Mail::to($user->email)->send(new UserWelcomeMail($user, $password));

        return $user;
    }
}
