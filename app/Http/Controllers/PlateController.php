<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\PlateRequest;

use App\Models\Plate;

class PlateController extends Controller
{
  public function index(PlateRequest $request)
  {
    $status = $request->input("status");

    $page = $request->input("page", "1");
    $rows = $request->input("rows", "10");

    $search = $request->input("search");

    $data = Plate::latest("updated_at")
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

  public function store(PlateRequest $request)
  {
    $data = Plate::create([
      "name" => $request->name,
    ]);

    return response()->saved("Plate", $data);
  }

  public function update(PlateRequest $request, $id)
  {
    $data = Plate::find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    $data->update([
      "name" => $request->name,
    ]);

    return response()->updated("Plate", $data);
  }

  public function destroy($id)
  {
    $data = Plate::withTrashed()->find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    if ($data->deleted_at) {
      $data->restore();

      return response()->restored("Plate", $data);
    }

    $data->delete();

    return response()->archived("Plate", $data);
  }
}
