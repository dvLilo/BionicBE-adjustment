<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Information extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $table = "information";

  protected $primaryKey = "ID";

  protected $fillable = ["category", "farm", "building", "buyer", "plate_no", "leadman", "checker", "current_date_in"];

  public function user()
  {
    return $this->hasOne(User::class, "mac_address", "mac_address");
  }

  public function heads()
  {
    return $this->hasMany(Transaction::class, "mac_address", "mac_address")
      ->where("id_foreign", $this->tablet_id)
      ->where("date_harvest", $this->current_date_in)
      ->select(DB::raw("SUM(transaction.heads) AS total"));
  }

  public function weight()
  {
    return $this->hasMany(Transaction::class, "mac_address", "mac_address")
      ->where("id_foreign", $this->tablet_id)
      ->where("date_harvest", $this->current_date_in)
      ->select(DB::raw("SUM(transaction.weight) AS total"));
  }
}
