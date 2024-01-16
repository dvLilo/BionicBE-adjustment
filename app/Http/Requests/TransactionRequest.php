<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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

        "from" => ["required_with:to", "exclude_with:date_harvest", "nullable", "date"],
        "to" => ["required_with:from", "exclude_with:date_harvest", "nullable", "date"],

        "date_harvest" => ["required_without:from,to", "exclude_with:from,to", "date"],
        "series_no" => ["required_with:date_harvest", "exclude_with:from,to", "numeric"],
        "transaction_no" => ["required_with:date_harvest", "exclude_with:from,to", "numeric"],

        "category" => "nullable",
        "farm" => "nullable",
        "building" => "nullable",
        "checker" => "nullable",
        "leadman" => "nullable",
        "buyer" => "nullable",
        "plate_no" => "nullable",
      ];
    }

    // Update Validation
    if ($this->isMethod("PUT")) {
      return [
        "heads" => ["required", "numeric"],
        "weight" => ["required", "numeric"],
      ];
    }
  }
}
