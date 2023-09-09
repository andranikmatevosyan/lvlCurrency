<?php

namespace App\Builders\Currency;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;

class CurrencyBuilder
{
    const BASE_URL = 'https://www.cbr.ru/scripts/XML_daily.asp';
    protected string $date;

    public function setDate($date): static
    {
        $this->validateDate($date);
        $this->date = $date;

        return $this;
    }

    public function validateDate($date): void
    {
        if (!Carbon::createFromFormat('d/m/Y', $date)) {
            throw new InvalidArgumentException('Invalid date format. Please use the format: Y-m-d');
        }
    }

    public function validateParams(): void
    {
        if (empty($this->date)) {
            throw new InvalidArgumentException('Date must be set before making the request.');
        }
    }

    public function get()
    {
        $this->validateParams();

        $response = Http::get(self::BASE_URL, [
            'date_req' => $this->date
        ]);

        if ($response->successful()) {
            return new SimpleXMLElement($response->body());
        } else {
            throw new RuntimeException('API request failed');
        }
    }
}

