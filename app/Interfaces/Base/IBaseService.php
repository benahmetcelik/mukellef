<?php

namespace App\Interfaces\Base;

use App\Http\Responses\ServiceResponse;
use Illuminate\Database\Eloquent\Model;

interface IBaseService
{


    /**
     * @param Model $model
     * @return self
     */
    public function setBaseModel(Model $model):self;

    /**
     * @return string
     */
    public function findModelName(): string;

    /**
     * @return Model
     */
    public function getBaseModel():Model;
}
