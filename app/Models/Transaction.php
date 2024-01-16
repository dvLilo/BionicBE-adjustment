<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use HasFactory;

  protected $table = "transaction";

  public function information()
  {
    return $this->hasOne(Information::class, "tablet_id", "id_foreign");
  }
}
