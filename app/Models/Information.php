<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Information extends Model
{
  use HasFactory, LogsActivity;

  public $timestamps = false;

  protected $table = "information";

  protected $primaryKey = "ID";

  protected $fillable = ["category", "farm", "building", "buyer", "plate_no", "leadman", "checker", "current_date_in"];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->setDescriptionForEvent(function ($event) {
        return "Information has been {$event}";
      })
      ->useLogName("information")
      ->logOnly(["*"]);
  }
}
