<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinkBumdesUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Update bumdes.user_id from users.bumdes_id for all 'user' role accounts
        DB::statement("UPDATE bumdes b INNER JOIN users u ON u.bumdes_id = b.id SET b.user_id = u.id WHERE u.role = 'user'");

        $this->command->info('Done! bumdes.user_id linked to user accounts successfully.');
    }
}
