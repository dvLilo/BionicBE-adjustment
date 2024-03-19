<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FarmRequest extends FormRequest
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
        "status" => ["required", "in:all,active,inactive"],

        "page" => ["nullable"],
        "rows" => ["nullable"],

        "search" => ["nullable"],
      ];
    }

    // Store Validation
    if ($this->isMethod("POST")) {
      return [
        "name" => ["required", "unique:farms,name"],
        "buildings.*" => ["required", "exists:buildings,id"],
      ];
    }

    // Update Validation
    if ($this->isMethod("PUT")) {
      return [
        "name" => ["required", "unique:farms,name," . $this->route("farm")],
        "buildings.*" => ["required", "exists:buildings,id"],
      ];
    }
  }

  public function messages()
  {
    return [
      "buildings.*.exists" => "The building is not registered.",
    ];
  }
}
