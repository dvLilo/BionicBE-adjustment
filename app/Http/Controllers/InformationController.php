<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\InformationRequest;

class InformationController extends Controller
{
  public function index(InformationRequest $request)
  {
    $request = $request->validated();

    $page = $request["page"] ?? 1;

    $date_from = $request["from"] ?? null;
    $date_to = $request["to"] ?? null;

    $date_harvest = $request["date_harvest"] ?? null;
    $series = $request["series_no"] ?? null;

    $category = $request["category"] ?? null;
    $farm = $request["farm"] ?? null;
    $building = $request["building"] ?? null;
    $plate_no = $request["plate_no"] ?? null;
    $checker = $request["checker"] ?? null;
    $leadman = $request["leadman"] ?? null;
    $buyer = $request["buyer"] ?? null;

    $data = DB::table("information")
      ->leftJoin("transaction", function ($join) {
        $join->on("transaction.id_foreign", "information.tablet_id");
        $join->on("transaction.mac_address", "information.mac_address");
        $join->on("transaction.date_harvest", "information.current_date_in");
      })
      ->leftJoin("user_account", function ($join) {
        $join->on("information.mac_address", "user_account.mac_address");
      })
      ->when(
        $date_harvest,
        fn($query) => $query->where("information.series", $series)->where("information.current_date_in", $date_harvest),
        fn($query) => $query->whereBetween("information.current_date_in", [$date_from, $date_to])
      )
      ->when($category, fn($query) => $query->where("information.category", $category))
      ->when($building, fn($query) => $query->where("information.building", $building))
      ->when($plate_no, fn($query) => $query->where("information.plate_no", $plate_no))
      ->when($checker, fn($query) => $query->where("information.checker", $checker))
      ->when($leadman, fn($query) => $query->where("information.leadman", $leadman))
      ->when($buyer, fn($query) => $query->where("information.buyer", $buyer))
      ->when($farm, fn($query) => $query->where("information.farm", $farm))
      ->select(
        "information.ID as id",

        "farm",
        "building",
        "category",
        "leadman",
        "checker",
        "buyer",
        "plate_no",

        DB::raw("CONCAT('Tab', user_account.ID, '-', information.series) AS series_no"),
        DB::raw("SUM(transaction.heads) AS total_heads"),
        DB::raw("SUM(transaction.weight) AS total_weight"),

        "information.current_date_in as date_harvest"
      )
      ->groupBy("information.ID")
      ->when($page == "none", fn($query) => $query->get(), fn($query) => $query->paginate());

    if ($data->isEmpty()) {
      return response()->doesntExist();
    }

    return $data;
  }

  public function update(Request $request, $id)
  {
    $data = DB::table("information")->where("ID", $id);

    if ($data->doesntExist()) {
      return response()->doesntExist();
    }

    $information = $data->first();

    $id_foreign = $information->tablet_id;
    $mac_address = $information->mac_address;
    $date_harvest = $information->current_date_in;

    $data->update([
      "farm" => $request["farm"],
      "leadman" => $request["leadman"],
      "building" => $request["building"],
      "current_date_in" => $request["date_harvest"],
      "buyer" => $request["buyer"],
      "plate_no" => $request["plate_no"],
      "category" => $request["category"],
      "checker" => $request["checker"],
    ]);

    if ($date_harvest != $request->date_harvest) {
      DB::table("transaction")
        ->where("id_foreign", $id_foreign)
        ->where("mac_address", $mac_address)
        ->where("date_harvest", $date_harvest)
        ->update([
          "date_harvest" => $request["date_harvest"],
        ]);
    }

    // log this activity here.

    return response()->updated("Information", $data->first());
  }
}
