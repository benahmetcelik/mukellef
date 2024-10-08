<?php

namespace App\Interfaces;

use App\Http\Responses\ServiceResponse;

interface IUserService
{

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     * @return ServiceResponse
     */
    public function register(string $email,string $password,string $name):ServiceResponse;

    /**
     * @param string $email
     * @return ServiceResponse
     */
    public function getByEmail(string $email):ServiceResponse;

    /**
     * @param mixed $filters
     * @param int $perPage
     * @return ServiceResponse
     */
    public function indexAnd(mixed $filters, int $perPage): ServiceResponse;

    /**
     * @param mixed $filters
     * @param int $perPage
     * @return ServiceResponse
     */
    public function indexWith(mixed $filters, int $perPage): ServiceResponse;
}
