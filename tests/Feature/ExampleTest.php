<?php

namespace Tests\Feature;

use App\Jobs\BillingJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPushTest()
    {
        Queue::fake();
        $response = $this->get('/api/bill');

        $response->assertStatus(200);
        Queue::assertPushedOn('billing', BillingJob::class);
        Queue::assertPushed(BillingJob::class);
    }
}
