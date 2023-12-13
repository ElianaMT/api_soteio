<?php

namespace App\Console\Commands;

use App\Mail\SendAwardToClient;
use App\Models\Award;
use App\Models\Client;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;

class SendAwards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-awards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia email de premios para clientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $date =  (new DateTime('now'))->format('Y-m-d H:i');
       $awards= Award::query()->where('date', '>=', $date)->get();

       foreach($awards as $award){
       $clients = Client::query()->take($award->amount)->inRandomOrder();

       foreach($clients as $client){
       Mail:: to($client->email,$client->name)
       ->send(new SendAwardToClient($client));
       }
    }
      
  }
}
