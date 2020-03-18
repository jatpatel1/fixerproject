<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;

/**
 * Get exchange rate using fixer.io API service.
 * @todo: Since this is a NON paid account, it looks like we can only get currency from Euro not GBP or any other currency.
 */
class FixerExchangeRateService
{

    /**
     * URL of fixer.io
     * @var string
     */
    private $url = "http://data.fixer.io/api/";

    /**
     * API key for fixer.io
     * @var string
     */
    private $access_key = '444102298cb9d5f2ba7de3f559232c2d';

    /**
     * Date when an historical call is made
     * @var string
     */
    private $date;

    private $ratesTo = 'all';

    public function __construct($ratesTo = null)
    {
        $this->ratesTo = $ratesTo;
    }

    public function getByLastBirthday($day, $month)
    {
        $dt = Carbon::now();
        $currentYear = $dt->year;
        $currentMonth = $dt->month;
        $currentDay = $dt->day;

        //Create start date of current year
        $startOfYear = Carbon::createFromDate($currentYear, 01, 01);
        //Create search date for birthday using current year and user inputs
        $bdate = Carbon::createFromDate($currentYear, $month, $day);
        //Work out how many days it has been from current date to user input date.
        $userInputLength = $dt->diffInDays($bdate);
        //Work out how many days it has been from current date to the start of year.
        $startOfYearLength = $dt->diffInDays($startOfYear);

        //Work out if the user specified day, month has passed in the current year by comparing it to the number of days that have past since year started. Combining Carbon isPast will allow us to truly determine if the date has passed todays date(this is because user's birthday year is set to this year in $bdate).
        if ($userInputLength <= $startOfYearLength && $bdate->isPast()) {
            $date = $bdate;
        } else { 
            $date = $bdate->subYear();
        }

        $this->date = $date->format('Y-m-d');
    }

    public function get()
    {
        $url = $this->buildUrl($this->url);

        try {
            $response = $this->makeRequest($url);

            return $this->prepareResponse($response);
        }

        catch (TransferException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'displayMessage' => 'Could not connect to Fixer API. Please try again.',
            ]);
        }
    }

    private function buildUrl($url)
    {
        if ($this->date) {
            $url .= $this->date;
        } else {
            $url .= 'latest';
        }

        $url .= '?access_key=' . $this->access_key;

        return $url;
    }

    private function makeRequest($url)
    {
        $guzzle = new GuzzleClient();
        $response = $guzzle->request('GET', $url);

        return $response->getBody();
    }

    private function prepareResponse($body)
    {
        $response = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => true,
                'message' => json_last_error_msg(),
                'displayMessage' => 'Error retrieving data.',
            ]);
        }

        if ($response['success'] === false) {
            return response()->json([
                'error' => true,
                'message' => $response['error']['info'], $response['error']['code'],
                'displayMessage' => 'Could not retrieve rates at this time. Please try again.',
            ]);
        }

        if (!is_array($response['rates'])) {
            return response()->json([
                'error' => true,
                'message' => 'Response body is malformed.',
                'displayMessage' => 'Could not retrieve rates at this time. Please try again.',
            ]);
        }

        $rates = $response['rates'];

        if ($this->ratesTo != 'all') {
            if (is_array($this->ratesTo)) {
                //@todo: implement for multiple rates
                return null;
            } elseif (array_key_exists($this->ratesTo, $rates)) {
                $rates = [$this->ratesTo => $rates[$this->ratesTo]];
            } else {
                return response()->json([
                    'error' => true,
                    'message' => $rates,
                    'displayMessage' => 'Could not retrieve specified rate(' . $this->ratesTo . ').',
                ]);
            }
        }

        return ['rates' => $rates, 'date' => $this->date];
    }

}