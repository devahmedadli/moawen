<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use App\Models\AgentTask;
use App\Models\ClientFile;
use App\Models\Subscription;
use App\Models\TgcSubscription;
use App\Notifications\GeneralNotification;

class NotificationService
{
    public function notifyAdmins(array $data)
    {
        User::where('role', 'admin')->each(function ($admin) use ($data) {
            $admin->notify(new GeneralNotification($data));
        });
    }

    public function notifyClient(array $data, $client)
    {
        if ($client) {
            $client->notify(new GeneralNotification($data));
        }
    }

    public function notifyFreelancer(array $data, $freelancer)
    {
        if ($freelancer) {
            $freelancer->notify(new GeneralNotification($data));
            
        }
    }

    public function clientCreated($client, $password)
    {
        $this->notifyAdmins([
            'title'         => 'New Client Created',
            'message'       => "Client {$client->name} has been created",
            'type'          => 'success',
            'model_id'      => $client->id,
            'model_type'    => 'Client',
            'action_url'    => route('clients.show', $client->id),
            'image'         => asset('storage/' . $client->avatar)
        ]);
    }

}
