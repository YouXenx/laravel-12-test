<?php

namespace App\Repositories;

use App\Models\SocialAssistance;
use App\Interfaces\SocialAssistanceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRepository implements SocialAssistanceRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute): Builder|Collection
    {
        $query = SocialAssistance::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage): LengthAwarePaginator
    {
        $query = SocialAssistance::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->paginate($rowPerPage ?? 10);
    }

    public function getById(string $id): ?SocialAssistance
    {
        return SocialAssistance::find($id);
    }

    public function create(array $data): SocialAssistance
    {
        DB::beginTransaction();
        try {
            $socialAssistance = SocialAssistance::create($data);
            DB::commit();
            return $socialAssistance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(string $id, array $data): SocialAssistance
    {
        DB::beginTransaction();
        try {
            $socialAssistance = SocialAssistance::find($id);
            if (isset($data['thumbnail'])) {
                $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');
            }
            $socialAssistance->name = $data['name'];
            $socialAssistance->category = $data['category'];
            $socialAssistance->amount = $data['amount'];
            $socialAssistance->provider = $data['provider'];
            $socialAssistance->description = $data['description'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();
            DB::commit();
            return $socialAssistance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id): int
    {
        DB::beginTransaction();
        try {
            $socialAssistance = SocialAssistance::find($id);
            $socialAssistance->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return 0;
    }
}
