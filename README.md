<h1 style="color:#283655;">HollaTags Interview Test</h1>


<h2 style="color:#4d648d;">About:</h2>
  This api is desgined to a solve the issue of time consumption in billing clients.

<h2 style="color:#4d648d;">Installation</h2>
- Clone this git repository
- run php artisan composer install
- setup your .env file
       - setup your database
       - change the QUEUE_CONNECTION to your desire (I used database)
- run php artisan migrate
- run db:seed to populate database
- run php artisan queue:listen database --queue=billing

<strong style="color:#3b5998">Note</strong>: you can run "php artisan test" to test the code


<h3 style="color:#ff6f69">Problem:</h2>
• An application for billing 10,000 users over a given billing API (owned by a third
party e.g. Telco/Bank).
• The billing API takes 1.6secs to process and respond to each request
• The details of the users to bill is stored in a Database with fields; id, username,
mobile_number and amount_to_bill

<h3 style="color:#ff6f69">Requirements:</h2>
Write or describe a simple PHP code that will be able bill all the 10,000 users within
1hr.

Also suggest an approach to scale the above if you need to bill 100,000 users within
4.5hrs


<h2 style="color:#00b159;">SUGGESTION</h2>
to scale the above billing, i would suggest the use of :
1. caching with redis since operations using caches are faster
2. you can run multiple queue instance to make the billing faster
3. increase the chuckByIn value according to your serve resource available
 
