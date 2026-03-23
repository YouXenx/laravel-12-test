<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = User::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        if ($limit) {
            $query->take($limit);
        }

        // jika execute = true, jalankan query
        if ($execute) {
            return $query->get(); // ❗ ganti getAll() → get()
        }

        // jika execute = false, return query builder
        return $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);

        return $query->paginate($rowPerPage);
    }


    public function getById(
        string $id
    ) {
        $query = User::where('id', $id);
        return $query->first();
    }

    public function create(
        array $data
    ) {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(
        string $id,
        array $data
    ) {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if (!$user) {
                throw new \Exception('User not found');
            }

            if (isset($data['name'])) {
                $user->name = $data['name'];
            }
            if (isset($data['email'])) {
                $user->email = $data['email'];
            }
            if (isset($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(
        string $id
    ) {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if (!$user) {
                throw new \Exception('User not found');
            }

            $user->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
