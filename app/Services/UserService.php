<?php

namespace App\Services;

use App\Http\Responses\ServiceResponse;
use App\Interfaces\IUserService;
use App\Models\SubscriptionV2;
use App\Models\User;
use App\Models\UserSubscription;
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


    /**
     * @param mixed $filters
     * @param int $perPage
     * @return ServiceResponse
     */
    public function indexAnd(mixed $filters, int $perPage): ServiceResponse
    {
        $model = User::query();
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $model = $model->where($filter->column, $filter->operation, $filter->value);
            }
        }
        $model = $model->with('transactions')
            ->with('subscriptions');
        $model = $model->paginate($perPage);
        return new ServiceResponse(true, 'Successful', $model, 200);
    }


    /**
     * @param mixed $filters
     * @param int $perPage
     * @return ServiceResponse
     */
    public function indexWith(mixed $filters, int $perPage): ServiceResponse
    {
        $model = User::query();
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $model = $model->where($filter->column, $filter->operation, $filter->value);
            }
        }
        $model = $model->with('subscriptions.transactions')
        ;
        $model = $model->paginate($perPage);
        return new ServiceResponse(true, 'Successful', $model, 200);
    }

}
