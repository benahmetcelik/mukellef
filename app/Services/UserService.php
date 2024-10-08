<?php

namespace App\Services;

use App\Http\Responses\ServiceResponse;
use App\Interfaces\IUserService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     * @return ServiceResponse
     */
    public function register(string $email, string $password, string $name): ServiceResponse
    {
        $checkEmail = $this->getByEmail($email);
        if (!$checkEmail->getIsSuccess()) {
            $data = User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'name' => $name
            ]);
            return new ServiceResponse(true, 'User created', $data, 201);
        }
        return $checkEmail;
    }

    /**
     * @param string $email
     * @return ServiceResponse
     */
    public function getByEmail(string $email): ServiceResponse
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            return new ServiceResponse(true, 'E-Mail exist', [], 200);
        }
        return new ServiceResponse(false, 'E-Mail does not exist', [], 404);
    }

    /**
     * @param string $email
     * @param string $password
     * @return ServiceResponse
     */
    public function login(string $email, string $password): ServiceResponse
    {

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return new ServiceResponse(false, 'Invalid credentials.', [], 401);
        }

        $user->token = $user->createToken(config('app.name'))->plainTextToken;

        return new ServiceResponse(true, 'Successful', $user, 200);

    }

}
