<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformationRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    // Index Validation
    if ($this->isMethod("GET")) {
      return [];
    }

    // Store Validation
    if ($this->isMethod("POST")) {
      return [
        "user_id" => ["required", "numeric", "exists:users,id"],
        "category_id" => ["required", "numeric", "exists:categories,id"],
        "farm_id" => ["required", "numeric", "exists:farms,id"],
        "building_id" => ["required", "numeric", "exists:buildings,id"],
        "leadman_id" => ["nullable", "numeric", "exists:leadmen,id"],
        "leadman_name" => ["required", "string"],
        "buyer_id" => ["nullable", "numeric", "exists:buyers,id"],
        "buyer_name" => ["required", "string"],
        "plate_id" => ["nullable", "numeric", "exists:plates,id"],
        "plate_name" => ["required", "string"],
        "type" => ["required", "string"],
        "series_no" => ["required", "numeric"],
        "harvested_at" => ["required", "date"],

        "transactions" => ["required", "array", "min:1"],
        "transactions.*.batch_no" => ["required", "numeric", "distinct"],
        "transactions.*.heads" => ["required", "numeric"],
        "transactions.*.weight" => ["required", "numeric"],
      ];
    }

    // Update Validation
    if ($this->isMethod("PUT")) {
      return [];
    }
  }
}
