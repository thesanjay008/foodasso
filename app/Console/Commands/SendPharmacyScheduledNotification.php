<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationSchedule;
use App\Models\User;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Api\BaseController as BaseController;

class SendPharmacyScheduledNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_pharmacy_scheduled_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = Carbon::now();
        $notification_schedule = NotificationSchedule::where('notified_at', null)
        ->where(['status' => 'pending' ])
        ->where(['is_approved' => '1' ])
        ->where('time_of_sending','<=',$time)
        ->get();


        // print_r($notification_schedule); die;

        foreach ($notification_schedule as $nsk => $ns) {
            // echo $ns->id;
            $ns->status = 'completed';
            $ns->notified_at = $time;
            $ns->save();
            
            $users = User::where([
                        'user_type' => $ns->type_of_user,
                        'status' => 'active',
                        'gender' => $ns->gender,
                    ])
                    ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR,users.dob,CURDATE())'),array($ns->age_from,$ns->age_to))
                    ->get();
            foreach ($users as $user) {
                // echo $user->id.'<br>';
                $notification = app('App\Http\Controllers\Api\BaseController')->sendNotificationNew($user, $ns->title, $ns->message, $ns->notification_type, $ns->type_id, '');
                // print_r($notification);
            }
            
            \Log::info("NotificationSchedule id : $ns->id Completed");
        }



        \Log::info("Cron is working fine! : SendPharmacyScheduledNotification");
    }
}
