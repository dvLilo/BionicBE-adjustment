<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    // Index Validation
    if ($this->isMethod("GET")) {
      return [
        "page" => ["nullable"],

        "from" => ["required", "date"],
        "to" => ["required", "date"],

        "type" => ["required", "in:DETAILED,SUMMARIZED"],
        "event" => ["nullable", "in:UPDATED,DELETED"],

        "name" => ["nullable"],

        "date" => ["nullable", "date"],
      ];
    }
  }
}
