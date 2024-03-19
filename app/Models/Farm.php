<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
  use HasFactory, SoftDeletes;

  protected $hidden = ["created_at"];

  protected $fillable = ["name"];

  public function buildings()
  {
    return $this->belongsToMany(Building::class, "farm_buildings", "farm_id", "building_id")->withPivot("id");
  }
}
