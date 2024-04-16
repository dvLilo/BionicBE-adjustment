<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
  use HasFactory, SoftDeletes;

  protected $hidden = ["created_at"];

  protected $fillable = ["information_id", "batch_no", "heads", "weight"];
}
