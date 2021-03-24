<?php

namespace App\Http\Controllers;



use App\Jobs\BillingJob;
use App\Models\Billing;
use App\Models\Progress;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BillController extends Controller
{
    public function index(){
        // call the job queue
//        BillingJob::dispatch()->onQueue('billing');

        // notBilled is a scope in the Billing model
        $billing_model = Billing::notBilled();
//        $progress = new Progress();
        // gets a collection of the billing model
        $billing_model->chunkById(40, function($users) use($billing_model){
            // loops through each of the chunk which is a array of 40 records
            $requestBody = [];
            foreach($users as $user){

//                return $billing_model;
                $requestBody[] = $this->add_billing_to_request($user);
                // call the thirdParty api
                // THIS IS A MOCK OF THE API CALL



//                print_r($user.PHP_EOL);

                    Progress::create([
                        'request' => $user
                    ]);


//                $user->update([
//                    'billed' => 1,
//                    'bill_date' => Carbon::now()
//                ]);

                $this->billUser($requestBody);
                $this->update_billed_user($user);
            }
//            print_r($requestBody);

        });

    }

    private function billUser(array $requestBody)
    {
        // TODO API call to third party
    }

    private function add_billing_to_request($user): array
    {
            return  [
                'id'=>$user->id,
                'username' => $user->username,
                'amount_to_bill' => $user->amount_to_bill
            ];

    }

    private function update_billed_user($billed_user){
        $billed_user->update([
            'billed' => 1,
            'bill_date' => Carbon::now()
        ]);
    }
}
