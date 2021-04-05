<?php

namespace Database\Seeders;

use App\Models\VentiRecord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VentiRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VentiRecord::truncate();
        $cases = $this->loadCSV(storage_path('app/mooping-salad.csv'));
        foreach ($cases as $case) {
            foreach (['created_at', 'dismissed_at', 'encountered_at', 'tagged_med_at', 'updated_at'] as $field) {
                if ($case[$field] !== null && $case[$field] !== 'null') {
                    $case[$field] = Carbon::parse($case[$field])->format('Y-m-d H:i:s');
                } else {
                    $case[$field] = null;
                }
            }
            foreach (['no', 'en', 'location', 'hn', 'name', 'cc', 'dx', 'triage', 'counter', 'insurance', 'outcome', 'vital_signs', 'remark'] as $field) {
                if ($case[$field] == 'null') {
                    $case[$field] = null;
                }
            }
            VentiRecord::create($case);
        }
    }

    protected function loadCSV($path)
    {
        if (! file_exists($path)) {
            return [];
        }
        $items = array_map('str_getcsv', file($path));
        array_walk($items, function (&$item) use ($items) {
            $item = array_combine($items[0], $item);
        });
        array_shift($items);

        return $items;
    }
}
