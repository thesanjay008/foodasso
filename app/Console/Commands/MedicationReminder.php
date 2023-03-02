<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\MedicalReminder;
use App\Models\ReminderDay;
use App\Models\User;



class MedicationReminder extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medication:reminder-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Customer Regarding Their Medication Reminder';

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
        //Get 0-6 Value Aaccording To Day Of Week
        $week      = Carbon::now()->dayOfWeek;

        //Get Active Reminders
        $reminders = MedicalReminder::where('status','active')->get();

        if($reminders->count() > 0){

            foreach ($reminders as $reminder) {
                
                //Check If Day Is Selected As Reminder Day
                $reminder_day = ReminderDay::where(['medical_reminder_id' => $reminder->id,'days' => $week])->first();

                if($reminder_day != null){

                    //Add Notification Code Here
                }

            }
        }
    }
}
