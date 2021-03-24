<?php

namespace App\Http\Controllers;



use App\Jobs\BillingJob;
use App\Models\Billing;


class BillController extends Controller
{
    /**
     * gets the billing model
     * and dispatches the BillingJob to the queue
     */
    public function index(){

        BillingJob::dispatch()->onQueue('billing');
    }


}
