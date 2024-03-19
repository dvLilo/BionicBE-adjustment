<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\BuyerRequest;

use App\Models\Buyer;

class BuyerController extends Controller
{
  public function index(BuyerRequest $request)
  {
    $status = $request->input("status");

    $page = $request->input("page", "1");
    $rows = $request->input("rows", "10");

    $search = $request->input("search");

    $data = Buyer::latest("updated_at")
      ->when($status === "all", function ($query) {
        return $query->withTrashed();
      })
      ->when($status === "inactive", function ($query) {
        return $query->onlyTrashed();
      })
      ->when($search, function ($query) use ($search) {
        return $query->where("name", "like", "%" . $search . "%");
      })
      ->when(
        $page === "none",
        function ($query) {
          return $query->get();
        },
        function ($query) use ($rows) {
          return $query->paginate($rows);
        }
      );

    if ($data->isEmpty()) {
      return response()->doesntExist();
    }

    return $data;
  }

  public function store(BuyerRequest $request)
  {
    $data = Buyer::create([
      "name" => $request->name,
    ]);

    return response()->saved("Buyer", $data);
  }

  public function update(BuyerRequest $request, $id)
  {
    $data = Buyer::find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    $data->update([
      "name" => $request->name,
    ]);

    return response()->updated("Buyer", $data);
  }

  public function destroy($id)
  {
    $data = Buyer::withTrashed()->find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    if ($data->deleted_at) {
      $data->restore();

      return response()->restored("Buyer", $data);
    }

    $data->delete();

    return response()->archived("Buyer", $data);
  }
}
