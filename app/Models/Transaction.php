<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transaction extends Model
{
  use HasFactory, LogsActivity;

  public $timestamps = false;

  protected $table = "transaction";

  protected $fillable = ["date_harvest", "heads", "weight"];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->setDescriptionForEvent(function ($event) {
        return "Transaction has been {$event}";
      })
      ->logOnly(["*"]);
  }
}
