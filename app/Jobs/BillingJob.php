<?php

namespace App\Jobs;

use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
     *   chunks the records to 5 (can be increased if desired) and calls an anonymous function
     *     function that loops through the collection and adds the "username and amount_to_bill"
     *       to the requestBody Array which is send to the Api for billing
     */
    public function handle()
    {
        // notBilled is a scope in the Billing model
        $billing_model = Billing::notBilled();

        // gets a collection of the billing model
        $billing_model->chunkById(40, function($users) use($billing_model){
            // loops through each of the chunk which is a array of 40 records
            foreach($users as $user){

                $requestBody[] = $this->add_billing_to_request($user);
                // call the thirdParty api
                // THIS IS A MOCK OF THE API CALL
                $this->billUser($requestBody);
                $this->update_billed_user($billing_model,$requestBody);
            }
        });

    }

    private function billUser(array $requestBody)
    {
        // TODO API call to third party
    }

    private function add_billing_to_request($user): array
    {
        $request  = [];
        foreach ($user as $bill_user){
            $request[]  =  [
                'id'=>$bill_user->id,
                'username' => $bill_user->username,
                'amount_to_bill' => $bill_user->amount_to_bill
            ];
        }
        return $request;

    }

    private function update_billed_user($billed_user,$request_body){
        $billed_user->whereIn('id',$request_body)->update([
                    'billed' => 1,
                    'bill_date' => Carbon::now()
             ]);
    }
}
