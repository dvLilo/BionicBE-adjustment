<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $table = "transaction";

  protected $fillable = ["date_harvest", "heads", "weight"];

  public function information()
  {
    return $this->hasOne(Information::class, "mac_address", "mac_address")
      ->where("tablet_id", $this->id_foreign)
      ->where("current_date_in", $this->date_harvest);
  }

  public function user()
  {
    return $this->hasOne(User::class, "mac_address", "mac_address");
  }
}
