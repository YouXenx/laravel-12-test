<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoryInterface;
use App\Models\Development;
use Exception;
use Illuminate\Support\Facades\DB;

class DevelopmentRepository implements DevelopmentRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = Development::query();

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
        $query = Development::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($rowPerPage ?? 10);
    }

    public function getById(string $id)
    {
        $query = Development::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $development = new Development();
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];

            $development->save();

            DB::commit();

            return $development;

        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th; // biar error ke controller
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $development = new Development();
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];

            $development->save();

            DB::commit();

            return $development;

        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th; // biar error ke controller
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $development = Development::find($id);
            $development->delete();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            throw new Exception($th->getMessage());
        }
    }
}