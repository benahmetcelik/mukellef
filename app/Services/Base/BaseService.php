<?php

namespace App\Services\Base;

use App\Http\Responses\ServiceResponse;
use App\Interfaces\Base\IBaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseService implements IBaseService
{
    private Model $baseModel;
    private bool $modelSetStatus = false;


    /**
     * @return string
     */
    public function findModelName(): string
    {
        $parentClass = get_class($this);
        $explodedClass = explode('\\',$parentClass);
        $className = last($explodedClass);
        return Str::remove('Service',$className);
    }

    /**
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function findModel(): Model
    {
        $model = $this->findModelName();
        return app()->make('App\\Models\\'.$model);
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setBaseModel(Model $model):self
    {
        $this->baseModel = $model;
        return $this;
    }

    /**
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getBaseModel():Model
    {
        if ($this->modelSetStatus){
            return  $this->baseModel;
        }
        $this->setBaseModel(
            $this->findModel()
        );
        return $this->baseModel;
    }
}
