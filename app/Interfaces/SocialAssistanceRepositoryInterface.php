<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\SocialAssistance;

interface SocialAssistanceRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute): Builder|Collection;

    public function getAllPaginated(?string $search, ?int $rowPerPage): LengthAwarePaginator;

    public function getById(string $id): ?SocialAssistance;

    public function create(array $data): SocialAssistance;

    public function update(string $id, array $data): SocialAssistance;

    public function delete(string $id): int;
}
