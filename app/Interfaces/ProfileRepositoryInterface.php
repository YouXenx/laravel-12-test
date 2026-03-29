<?php

namespace App\Interfaces;

interface ProfileRepositoryInterface
{
    public function getProfile();

    public function create(array $data);

    public function update(array $data);
}