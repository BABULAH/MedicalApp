<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeSlot;
use App\Models\Availability;
use Carbon\Carbon;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $availabilities = Availability::all();

        if ($availabilities->isEmpty()) {
            $this->command->info('Veuillez d’abord seed les availabilities.');
            return;
        }

        foreach ($availabilities as $availability) {

            // start_time et end_time sont déjà des Carbon grâce au cast
            $start = Carbon::parse($availability->start_time);
            $end   = Carbon::parse($availability->end_time);

            // Créneaux de 1h
            while ($start->lt($end)) {
                $slotEnd = $start->copy()->addHour();

                if ($slotEnd->gt($end)) {
                    $slotEnd = $end->copy();
                }

                TimeSlot::create([
                    'availability_id' => $availability->id,
                    'establishment_id'=> $availability->establishment_id, // multi-tenant
                    'start_time'      => $start->format('H:i:s'),
                    'end_time'        => $slotEnd->format('H:i:s'),
                    'is_booked'       => false,
                ]);

                $start->addHour();
            }
        }
    }
}
