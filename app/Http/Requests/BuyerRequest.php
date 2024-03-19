<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerRequest extends FormRequest
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
        "name" => ["required", "unique:buyers,name"],
      ];
    }

    // Update Validation
    if ($this->isMethod("PUT")) {
      return [
        "name" => ["required", "unique:buyers,name," . $this->route("buyer")],
      ];
    }
  }
}
