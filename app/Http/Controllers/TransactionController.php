<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\DropdownRequest;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
  public function index(TransactionRequest $request)
  {
    $request = $request->validated();

    $page = $request["page"] ?? 1;

    $date_from = $request["from"] ?? null;
    $date_to = $request["to"] ?? null;

    $date_harvest = $request["date_harvest"] ?? null;
    $series = $request["series_no"] ?? null;
    $transaction_no = $request["transaction_no"] ?? null;

    $category = $request["category"] ?? null;
    $farm = $request["farm"] ?? null;
    $building = $request["building"] ?? null;
    $plate_no = $request["plate_no"] ?? null;
    $checker = $request["checker"] ?? null;
    $leadman = $request["leadman"] ?? null;
    $buyer = $request["buyer"] ?? null;

    $data = DB::table("transaction")
      ->join("information", function ($join) {
        $join->on("transaction.id_foreign", "information.tablet_id");
        $join->on("transaction.mac_address", "information.mac_address");
        $join->on("transaction.date_harvest", "information.current_date_in");
      })
      ->join("user_account", function ($join) {
        $join->on("information.mac_address", "user_account.mac_address");
      })
      ->when(
        $series && $transaction_no && $date_harvest,
        fn($query) => $query
          ->where("transaction.transaction_no", $transaction_no)
          ->where("information.series", $series)
          ->where("information.current_date_in", $date_harvest),
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
        "transaction.id",

        "transaction_no",
        "date_harvest",
        "heads",
        "weight",
        DB::raw("transaction.weight / transaction.heads AS allowance"),
        "category",
        "farm",
        "building",
        "buyer",
        "plate_no",
        "leadman",
        "checker",
        DB::raw("CONCAT('Tab', user_account.ID, '-', information.series) AS series_no"),

        "transaction.current_date_in",
        "transaction.current_time_in"
      )
      ->when($page == "none", fn($query) => $query->get(), fn($query) => $query->paginate());

    if ($data->isEmpty()) {
      return response()->doesntExist();
    }

    return $data;
  }

  public function update(TransactionRequest $request, $id)
  {
    $data = DB::table("transaction")->where("id", $id);

    if ($data->doesntExist()) {
      return response()->doesntExist();
    }

    $data->update([
      "heads" => $request["heads"],
      "weight" => $request["weight"],
    ]);

    // log this activity here.

    return response()->updated("Transaction", $data->first());
  }

  public function destroy($id)
  {
    $data = DB::table("transaction")->where("id", $id);

    if ($data->doesntExist()) {
      return response()->doesntExist();
    }

    $transaction = $data->first();

    $duplicate = DB::table("transaction")
      ->where("id_foreign", $transaction->id_foreign)
      ->where("mac_address", $transaction->mac_address)
      ->where("transaction_no", $transaction->transaction_no)
      ->where("date_harvest", $transaction->date_harvest)
      ->where("heads", $transaction->heads)
      ->where("weight", $transaction->weight)
      ->get()
      ->count();

    if ($duplicate <= 1) {
      return response()->doesntDuplicate();
    }

    DB::table("softdeleted")->insert([
      "id" => $transaction->id,
      "date_harvest" => $transaction->date_harvest,
      "heads" => $transaction->heads,
      "weight" => $transaction->weight,
      "current_date_in" => $transaction->current_date_in,
      "current_time_in" => $transaction->current_time_in,
      "id_foreign" => $transaction->id_foreign,
      "mac_address" => $transaction->mac_address,
      "transaction_no" => $transaction->transaction_no,
      "created_at" => $transaction->created_at,
    ]);
    DB::table("transaction")->delete($transaction->id);

    // log this activity here.

    return response()->deleted("Transaction", []);
  }

  public function dropdown(DropdownRequest $request)
  {
    $column = $request["name"];

    return DB::table("information")
      ->distinct()
      ->get($column)
      ->pluck($column);
  }
}
