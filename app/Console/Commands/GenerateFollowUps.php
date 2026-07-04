<?php

namespace App\Console\Commands;

use App\Models\FollowUp;
use App\Models\SolarClient;
use Illuminate\Console\Command;

class GenerateFollowUps extends Command
{
    protected $signature = 'followups:generate';

    protected $description = 'Generate solar follow-up reminders from each client latest visit.';

    public function handle(): int
    {
        $created = 0;

        SolarClient::query()->whereNotNull('reminder_date')->chunkById(100, function ($clients) use (&$created) {
            foreach ($clients as $client) {
                $followUp = FollowUp::firstOrCreate([
                    'solar_client_id' => $client->id,
                    'due_date' => $client->reminder_date->toDateString(),
                    'source' => 'solar_latest_visit',
                ]);

                if ($followUp->wasRecentlyCreated) {
                    $created++;
                }
            }
        });

        $this->info("Generated {$created} follow-up reminder(s).");

        return self::SUCCESS;
    }
}
