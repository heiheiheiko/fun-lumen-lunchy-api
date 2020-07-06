<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CrudRepositoryInterface
{
    public function create(Request $request);

    public function update(Request $request, int $id);

    public function all();

    public function find($id);

    public function delete($id);
}
