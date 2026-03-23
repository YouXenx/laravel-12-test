<?php

namespace App\Repositories;

use App\Models\FamilyMember;
use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Exception;


class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $query = FamilyMember::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

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

        $query = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $query = FamilyMember::where('id', $id)->with('headOfFamily');
        return $query->first();
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
                'role' => 'family_member',
            ]);

            $familyMember = FamilyMember::create([
                'user_id' => $user->id,
                'head_of_family_id' => $data['head_of_family_id'],
                'profile_picture' => $data['profile_picture'] ?? null,
                'identity_number' => $data['identity_number'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'occupation' => $data['occupation'],
                'material_status' => $data['material_status'],
                'relation' => $data['relation'],
            ]);
            DB::commit();
            return $familyMember;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(
        String $id,
        array $data
    ) {
        DB::beginTransaction();
        try {
            $familyMember = FamilyMember::find($id);
            if (isset($data['profile_picture'])) {
                $familyMember->profile_picture = $data['profile_picture']->store('assets/profile_pictures', 'public');
            }

            $familyMember->identity_number = $data['identity_number'] ?? $familyMember->identity_number;
            $familyMember->gender = $data['gender'] ?? $familyMember->gender;
            $familyMember->date_of_birth = $data['date_of_birth'] ?? $familyMember->date_of_birth;
            $familyMember->phone_number = $data['phone_number'] ?? $familyMember->phone_number;
            $familyMember->occupation = $data['occupation'] ?? $familyMember->occupation;
            $familyMember->material_status = $data['material_status'] ?? $familyMember->material_status;
            $familyMember->relation = $data['relation'] ?? $familyMember->relation;

            $familyMember->save();
            DB::commit();

            $userRepository = new UserRepository();
            $userRepository->update($familyMember->user_id, [
                'name' => $data['name'] ?? null,
                'email' => $data['email'] ?? null,
                'password' => $data['password'] ?? null,
            ]);

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete(String $id)
    {
        DB::beginTransaction();
        try {
            $familyMember = $this->getById($id);
            if (!$familyMember) {
                throw new Exception('Family Member tidak ditemukan');
            }

            $userRepository = new UserRepository();
            $userRepository->delete($familyMember->user_id);

            $familyMember->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
