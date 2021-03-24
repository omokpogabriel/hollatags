<?php

namespace App\Http\Controllers;



use App\Jobs\BillingJob;
use App\Models\Billing;


class BillController extends Controller
{
    public function index(){
        $billing = Billing::all();
        BillingJob::dispatch($billing)->onQueue('billing');
    }

}
