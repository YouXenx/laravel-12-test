<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialAssistance;
use App\Models\HeadOfFamily;
use Database\Factories\SocialAssistanceRecipientFactory;
use Database\Factories\SocialAssistanceFactory;
use Database\Factories\HeadOfFamilyFactory;

class SocialAssistanceRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialAssistanceRecipients = SocialAssistance::all();
        $headOfFamilies = HeadOfFamily::all();
        foreach ($socialAssistanceRecipients as $socialAssistance) {
            foreach ($headOfFamilies as $headOfFamily) {
                SocialAssistanceRecipientFactory::new()->create([
                    'social_assistance_id' => $socialAssistance->id,
                    'head_of_family_id' => $headOfFamily->id,
                ]);
            }
        }
    }
}
