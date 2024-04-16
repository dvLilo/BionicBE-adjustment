<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Information extends Model
{
  use HasFactory, SoftDeletes;

  protected $hidden = ["created_at"];

  protected $fillable = [
    "user_id",
    "category_id",
    "farm_id",
    "building_id",
    "leadman_id",
    "leadman_name",
    "buyer_id",
    "buyer_name",
    "plate_id",
    "plate_name",
    "type",
    "series_no",
    "harvested_at",
  ];

  public function category()
  {
    return $this->hasOne(Category::class, "id", "category_id");
  }

  public function farm()
  {
    return $this->hasOne(Farm::class, "id", "farm_id");
  }

  public function building()
  {
    return $this->hasOne(Building::class, "id", "building_id");
  }

  public function leadman()
  {
    return $this->hasOne(Leadman::class, "id", "leadman_id");
  }

  public function buyer()
  {
    return $this->hasOne(Buyer::class, "id", "buyer_id");
  }

  public function plate()
  {
    return $this->hasOne(Plate::class, "id", "plate_id");
  }
}
