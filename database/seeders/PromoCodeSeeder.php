<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\PromoCode;
use Illuminate\Support\Str;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pick existing events or fallback
        $events = Event::with('eventTicketTypes')->get();

        if ($events->isEmpty()) {
            $this->command->warn('No events found! Please seed events first.');
            return;
        }

        // Define realistic group scenarios
        $groups = [
            [
                'group_name' => 'VIP Sponsor Batch 2026',
                'discount'   => 10000,
                'count'      => 15,
                'used_ratio' => 0.4, // ~40% used
            ],
            [
                'group_name' => 'Early Bird Special Discount',
                'discount'   => 5000,
                'count'      => 30,
                'used_ratio' => 0.7, // ~70% used
            ],
            [
                'group_name' => 'Yangon Marathon Partner Pass',
                'discount'   => 15000,
                'count'      => 10,
                'used_ratio' => 0.2, // ~20% used
            ],
            [
                'group_name' => 'Influencer Giveaways',
                'discount'   => 8000,
                'count'      => 25,
                'used_ratio' => 0.8, // ~80% used
            ],
            [
                'group_name' => 'Corporate Relay Team Offer',
                'discount'   => 20000,
                'count'      => 12,
                'used_ratio' => 0.5, // ~50% used
            ],
            [
                'group_name' => 'Community Runner Discount',
                'discount'   => 3000,
                'count'      => 50,
                'used_ratio' => 0.3, // ~30% used
            ],
        ];

        foreach ($groups as $group) {
            // Select a random event
            $event = $events->random();

            // Optionally assign to a specific ticket type or leave null (All Categories)
            $ticketType = rand(0, 1) ? $event->eventTicketTypes->random() : null;

            for ($i = 1; $i <= $group['count']; $i++) {
                // Generate unique code string
                $prefix = Str::upper(Str::slug(explode(' ', $group['group_name'])[0]));
                $code = $prefix . '-' . Str::upper(Str::random(6));

                // Determine if code is used based on ratio
                $isUsed = (rand(1, 100) / 100) <= $group['used_ratio'];

                $promoCode = PromoCode::create([
                    'group_name' => $group['group_name'],
                    'code'       => $code,
                    'amount'     => $group['discount'],
                    'event_id'   => $event->id,
                    'is_used'    => $isUsed,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // Attach pivot relationship if specific ticket type applies
                if ($ticketType) {
                    $promoCode->eventTicketTypes()->attach($ticketType->id);
                }
            }
        }

        $this->command->info('Successfully seeded multiple promo code groups!');
    }
}