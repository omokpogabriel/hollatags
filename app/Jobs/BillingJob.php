<?php

namespace App\Jobs;

use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BillingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Execute the job.
     *
     * @return void
     *
     * The handle gets all the records marked as not billed ( value of 0),
     *   chunks the records to 40 (can be increased if desired) and calls an anonymous function
     *     function that loops through the collection and adds the "username and amount_to_bill"
     *       to the requestBody Array which is send to the Api for billing
     */
    public function handle()
    {
        $billing_model = Billing::notBilled();
        $billing_model->chunkById(40, function($users) use($billing_model){
            $requestBody = [];

            // loops through the users to be billed
            foreach($users as $user){

                // calls the add_to_request function
                $requestBody[] = $this->add_billing_to_request($user);

                // call the mocked billing api
                $this->billUser($requestBody);

                // calls the update_billed_user
                $this->update_billed_user($user);
            }
        });

    }

    // empty function where the api to bill the user should be implemented
    private function billUser(array $requestBody)
    {
        // TODO API call to third party
    }

    // filters the user parameter and sends returns a array containing
    // only relevant fileds to be send to the billing api
    private function add_billing_to_request($user): array
    {
        return  [
            'id'=>$user->id,
            'username' => $user->username,
            'amount_to_bill' => $user->amount_to_bill
        ];

    }

    //sets the billed filed tp 1 and updates the bill_date field
    private function update_billed_user($billed_user){
        $billed_user->update([
            'billed' => 1,
            'bill_date' => Carbon::now()
        ]);
    }
}
