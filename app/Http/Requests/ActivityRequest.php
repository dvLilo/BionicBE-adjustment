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
        "page" => ["required"],

        "type" => ["nullable", "in:detailed,summarized"],
        "event" => ["nullable", "in:updated,deleted"],

        "name" => ["nullable"],

        "date" => ["nullable", "date"],
      ];
    }
  }
}
