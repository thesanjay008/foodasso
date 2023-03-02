<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Validator;
use DB,Settings;
use App\Http\Controllers\Api\BaseController;
use App\Models\MedicalReminder;
use App\Models\ReminderDay;
use App\Models\User;

class CronController extends BaseController
{

    /**
     * Show the application CRON JOBS.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reminderCron(){
        
        $return = 'Not run';

        $time = '06:00 PM';
        $reminders = MedicalReminder::where(['time'=>$time])->get();

        foreach($reminders as $list){
            $user = User::where(['id'=>$list->owner_id])->first();
            $return = $this->sendNotificationNew($user,'Medication Reminder for '.$list->title,'Medication Reminder for '.$list->title .' Dosage '.$list->dosage,'MedicationReminder',$list->id);
        }
        print_r($return); exit;
    }

    public function changeUserDOB(){

        $users = User::get();
        foreach($users as $key=> $list){
            $users = User::where('id', $list->id)->update(['dob' => date('Y-m-d', strtotime($list->dob))]);
        }
    }
}
