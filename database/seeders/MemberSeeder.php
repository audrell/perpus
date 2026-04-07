<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\Member::create([
                'user_id' => $user->id,
                'member_code' => \App\Models\Member::generateNextMemberCode(),
                'name' => $user->name,
                'is_active' => 1,
            ]);
        }
    }
}
