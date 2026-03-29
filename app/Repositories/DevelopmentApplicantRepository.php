<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentApplicantRepositoryInterface;

use App\Models\DevelopmentApplicant;
use Exception;
use Illuminate\Support\Facades\DB;

class DevelopmentApplicantRepository implements DevelopmentApplicantRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = DevelopmentApplicant::query(); // ✔ pakai model yang benar

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $query->orderBy('created_at', 'desc');

        if ($limit) {
            $query->take($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = DevelopmentApplicant::query();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($rowPerPage ?? 10);
    }

    public function getById(string $id)
    {
        $query = DevelopmentApplicant::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $developmentApplicant = new DevelopmentApplicant();
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];

            // ✅ fix status
            $developmentApplicant->status = $data['status'] ?? 'pending';

            $developmentApplicant->save();

            DB::commit(); // ✅ jangan lupa commit

            return $developmentApplicant; // ✅ WAJIB

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];
            $developmentApplicant->status = $data['status'] ?? 'pending';
            $developmentApplicant->save();

            DB::commit();

            return $developmentApplicant;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }

    public function delete(string $id)
    {
        $applicant = DevelopmentApplicant::findOrFail($id);
        $applicant->delete();
    }
}