<?php

namespace Database\Seeders;

use App\Models\CannedResponse;
use Illuminate\Database\Seeder;

class CannedResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responses = [
            ['title' => 'Restart PC', 'content' => 'Please try restarting your computer to see if that resolves the issue.'],
            ['title' => 'Clear Cache', 'content' => 'Please try clearing your browser cache and cookies, then reload the page.'],
            ['title' => 'Check Cables', 'content' => 'Could you please verify that all cables (power and network) are securely plugged in?'],
            ['title' => 'Ticket Resolved', 'content' => 'We have applied a fix for this issue. Please check on your end and let us know if everything is working correctly now.'],
        ];

        foreach ($responses as $res) {
            CannedResponse::create($res);
        }
    }
}
