<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function get()
    {
        return Profile::first();
    
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = new Profile();
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];

            // WAJIB save dulu sebelum relasi
            $profile->save();

            // Handle multiple images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public'),
                    ]);
                }
            }

            DB::commit();

            return $profile;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th; // biar error keliatan
        }
    }

    public function udpate(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = Profile::first();

            if (isset($data['thumbanil'])) {
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];

            // WAJIB save dulu sebelum relasi
            $profile->save();

            // Handle multiple images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public'),
                    ]);
                }
            }

            DB::commit();

            return $profile;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th; // biar error keliatan
        }
    }
}