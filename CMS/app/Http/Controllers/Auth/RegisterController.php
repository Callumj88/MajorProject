<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{

    // Where to redirect after registration
    protected $redirectTo = '/users';

    public function __construct()
    {
        // Only allow access to this controller if the user is already logged in
        $this->middleware('auth');
    }

    /**
     * Show the registration form to logged-in users
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request
     */
    public function register(Request $request)
    {
        // Validate the data using the validator below
        $this->validator($request->all())->validate();

        // Create user using the method below
        $user = $this->create($request->all());

        //  send verification email.  works for my account, need to pay for smtp email provider for others
        //event(new Registered($user));

        // just redirect back
        return redirect()->route('users')->with('success', 'User registered successfully.');    }

    /**
     * Validate the incoming data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:50'],
        ]);
    }

    /**
     * Actually create the user in the DB
     */
    protected function create(array $data)
    {
        $authLevel = isset($data['is_admin']) ? 1 : 0;

        return User::create([
            'firstName'  => $data['firstName'],
            'lastName'   => $data['lastName'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'authLevel'  => $authLevel,
            'role'       => $data['role'] ?? null,
        ]);
    }
}
