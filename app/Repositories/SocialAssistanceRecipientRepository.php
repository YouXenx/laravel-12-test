<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;



class SocialAssistanceRecipientRepository implements SocialAssistanceRecipientRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Builder|Collection {
        $query = SocialAssistanceRecipient::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $query = SocialAssistanceRecipient::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%"); 
        }

        return $query->paginate($rowPerPage ?? 10);
    }

    public function getById(string $id)
{
    return SocialAssistanceRecipient::with([
        'socialAssistance',
        'headOfFamily'
    ])->find($id);
}

    public function create(array $data) {
        DB::beginTransaction();
        try {
            $socialAssistanceRecipient = new SocialAssistanceRecipient();
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];

            if (isset($data['proof'])) {
                $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients', 'public');
            }
            if (isset($data['status'])) {
                $socialAssistanceRecipient->status = $data['status'];
            }
            $socialAssistanceRecipient->save();
            DB::commit();
            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(string $id, array $data) {
        DB::beginTransaction();
        try {
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];

            if (isset($data['proof'])) {
                $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients', 'public');
            }
            if (isset($data['status'])) {
                $socialAssistanceRecipient->status = $data['status'];
            }
            $socialAssistanceRecipient->save();
            DB::commit();
            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id) {
        return SocialAssistanceRecipient::find($id)->delete();
    }
}

