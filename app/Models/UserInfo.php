<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
class UserInfo extends Model
{
	protected $table = 'user_info';
    protected $fillable = [
		'user_id','state_id','city_id','category_id','organization_id','license_id','language','mode_of_booking','qualification','charges',
		'bio','height','weight','age','language','speciality','rating','review','is_delivery_nitification',
		'address','latitude','longitude','license','pancard','adharCard','status','document_type','documentfile_front','documentfile_back',
		'start_time','end_time','documentfile','is_occupie'
	];
}