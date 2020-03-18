<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Services\FixerExchangeRateService;
use App\BirthdayCurrencySearch;

class HomeController extends Controller
{
    public function index()
    {
        $dt = Carbon::now();
        $months = [];
        for ($m=1; $m<=12; ++$m) {
            $newDate = Carbon::createFromDate(0, $m, 1);
            $months[$newDate->format('m')] = $newDate->format('F');
        }

        $days = [];
        for ($d=1; $d<=31; ++$d) {
            $days[] = $d;
        }

        $results = BirthdayCurrencySearch::selectRaw('*, DATEDIFF(`birthday`, CURDATE()) AS diff, count(id) as groupCount')
        ->groupBy('birthday')
        ->orderBy('diff', 'desc')
        ->get();

        return view('home.index', compact('months', 'days', 'results'));
    }

    public function search(Request $request)
    {
        $dt = Carbon::now();
        $allInputs = $request->all();
        $name = $request->input('txtName');
        $birthDay = $request->input('txtBirthDay');
        $birthMonth = $request->input('txtBirthMonth');

        if ($name == '') {
            return Redirect::back()->withErrors('Sorry, your name cannot be empty.');
        }

        $currency = new FixerExchangeRateService('GBP');
        $currency->getByLastBirthday($birthDay, $birthMonth);
        $res = $currency->get();

        if (is_object($res)) {
            $res = $res->getData();
            if ($res->error) {
                return Redirect::back()->withErrors($res->displayMessage);
            }

        } elseif (is_array($res)) {
            $currency = array_keys($res['rates'])[0];
            $rate = $res['rates'][$currency];
            $birthdayDate = $res['date'];

            $birthdayCurrency = new BirthdayCurrencySearch();
            $birthdayCurrency->name = $name;
            $birthdayCurrency->rate = $rate;
            $birthdayCurrency->currency = $currency;
            $birthdayCurrency->birthday = $birthdayDate;
            $birthdayCurrency->save();
        }

        return Redirect::back();
    }

}
