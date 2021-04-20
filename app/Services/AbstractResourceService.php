<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface AbstractResourceService
{
    public function store(array $data);

    public function update(Model $model, array $data);

    public function delete(Model $model);

    public function show(Model $model): Model;
}
