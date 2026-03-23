<?php

namespace App\Repositories;

use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Models\HeadOfFamily;
use Exception;
use Illuminate\Support\Facades\DB;
use Phiki\Support\Str;
use Illuminate\Support\Facades\Storage;


class HeadOfFamilyRepository implements HeadOfFamilyRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $query = HeadOfFamily::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        $query->orderBy('created_at', 'desc');

        if ($limit) {
            $query->take($limit);
        }

        // penting!
        return $execute ? $query->get() : $query;
    }


    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $query = HeadOfFamily::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        return HeadOfFamily::with([
            'user',
            'familyMembers.user'
        ])->where('id', $id)->first();
    }


    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $userRepository = new UserRepository();
            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            // === FIX UPLOAD FILE === //
            $file = $data['profile_picture']; // UploadedFile

            // Simpan ke storage/app/public/assets/head-of-families
            $path = $file->store('assets/head-of-families', 'public');

            $headOfFamily = new HeadOfFamily();
            $headOfFamily->user_id = $user->id;

            // Simpan path (contoh: "assets/head-of-families/xxxx.jpg")
            $headOfFamily->profile_picture = $path;

            $headOfFamily->identity_number = $data['identity_number'];
            $headOfFamily->gender = $data['gender'];
            $headOfFamily->date_of_birth = $data['date_of_birth'];
            $headOfFamily->phone_number = $data['phone_number'];
            $headOfFamily->occupation = $data['occupation'];
            $headOfFamily->material_status = $data['material_status'];

            $headOfFamily->save();

            DB::commit();
            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(
        String $id,
        array $data
    ) {
        DB::beginTransaction();
        try {
            $headOfFamily = $this->getById($id);
            if (!$headOfFamily) {
                throw new Exception('Head Of Family tidak ditemukan');
            }

            // Update user terkait
            $userRepository = new UserRepository();
            $userRepository->update($headOfFamily->user_id, [
                'name' => $data['name'],
                'email' => $data['email'],
                // Hanya update password jika disediakan
                'password' => $data['password'] ?? null,
            ]);

            // Perbarui atribut HeadOfFamily
            if (isset($data['profile_picture'])) {
                // Hapus file lama jika ada
                if ($headOfFamily->profile_picture) {
                    Storage::disk('public')->delete($headOfFamily->profile_picture);
                }

                // Simpan file baru
                $file = $data['profile_picture'];
                $path = $file->store('assets/head-of-families', 'public');
                $headOfFamily->profile_picture = $path;
            }

            $headOfFamily->identity_number = $data['identity_number'];
            $headOfFamily->gender = $data['gender'];
            $headOfFamily->date_of_birth = $data['date_of_birth'];
            $headOfFamily->phone_number = $data['phone_number'];
            $headOfFamily->occupation = $data['occupation'];
            $headOfFamily->material_status = $data['material_status'];

            $headOfFamily->save();

            DB::commit();
            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    public function delete(String $id)
    {
        DB::beginTransaction();
        try {
            $headOfFamily = $this->getById($id);
            if (!$headOfFamily) {
                throw new Exception('Head Of Family tidak ditemukan');
            }

            // Hapus file profile picture jika ada
            if ($headOfFamily->profile_picture) {
                Storage::disk('public')->delete($headOfFamily->profile_picture);
            }

            // Hapus user terkait
            $userRepository = new UserRepository();
            $userRepository->delete($headOfFamily->user_id);

            // Hapus data HeadOfFamily
            $headOfFamily->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
