<?php

namespace App\Repositories\Interfaces;

interface RepositoryInterfaceAbstract
{
    function findAll();

    function find($id);

    function insert(array $input): int;

    function update($id, array $input);
}