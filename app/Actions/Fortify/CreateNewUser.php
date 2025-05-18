<?php

namespace App\Actions\Fortify;

use App\Models\Doctor;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

//Esto es para registrar un nuevo usuraio/doctor

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //esto asegura que solo se acepten estos 2 tipos de usuario
            'type' => ['required', 'string', 'in:user,doctor'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'type' => "doctor", //solo permite a los doctores registrarse e iniciar sesion
            'password' => Hash::make($input['password']),
        ]);

        $doctorInfo = Doctor::create([
            'doc_id' => $user->id,
            'status' => 'active',
            ]
        );

        return $user;
    }
}
