<?php

namespace App\Interfaces;

interface CrudRepositoryInterface
{
    public function create(array $attributes);

    public function update(array $attributes);

    public function all();

    public function find($id);

    public function delete($id);
}
