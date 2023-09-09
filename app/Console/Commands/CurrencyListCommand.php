<?php

namespace App\Console\Commands;

use App\Builders\Currency\CurrencyBuilder;
use App\Helpers\DateHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CurrencyListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command gets currencies from CBR and prints them';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $response = [];
        $weekDays = DateHelper::currentWeekDays();

        foreach ($weekDays as $weekDay) {
            $this->warn("Script is working for {$weekDay->format('d/m/Y')} day");

            $currencyList = (new CurrencyBuilder())
                ->setDate($weekDay->format('d/m/Y'))
                ->get();

            $date = (string)$currencyList->attributes()['Date'];

            foreach ($currencyList->children() as $child) {
                $response[$date][] = [
                    'ID' => (string)$child->attributes()['ID'],
                    'NumCode' => (string)$child->NumCode,
                    'CharCode' => (string)$child->CharCode,
                    'Nominal' => (string)$child->Nominal,
                    'Name' => (string)$child->Name,
                    'Value' => (string)$child->Value
                ];
            }

            $this->info("Script finished working for {$weekDay->format('d/m/Y')} day");
        }

        print_r($response);
    }
}
