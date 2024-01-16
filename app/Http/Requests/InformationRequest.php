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
      return [
        "page" => ["nullable"],

        "from" => ["required_with:to", "date"],
        "to" => ["required_with:from", "date"],

        "date_harvest" => ["required_without:from,to", "exclude_with:from,to", "date"],
        "series_no" => ["required_without:from,to", "exclude_with:from,to", "numeric"],

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
        "date_harvest" => ["required", "date"],
        "category" => ["required", "in:RDF,BIYAHERO"],
        "farm" => [
          "required",
          "in:LARA 1,LARA 2,RANGER,PORAC,TREKKER,CALSARA,SANTOS,DIZONPORAC,PULUNGSANTOL,MAGALANG,GOLDEN EAGLE,CAPAS,UMINGAN,MONCADA",
        ],
        "building" => [
          "required",
          "in:Bldg 1,Bldg 2,Bldg 3,Bldg 4,Bldg 5,Bldg 6,Bldg 7,Bldg 8,Bldg 9,Bldg 10,Bldg 1A,Bldg 1B,Bldg 2A,Bldg 2B,Bldg 3A,Bldg 3B,Bldg 4A,Bldg 4B,Bldg 5A,Bldg 5B,Bldg 6A,Bldg 6B,Bldg 7A,Bldg 7B,Bldg 8A,Bldg 8B",
        ],
        "leadman" => "required",
        "checker" => "required",
        "buyer" => "required",
        "plate_no" => "required",
      ];
    }
  }
}
