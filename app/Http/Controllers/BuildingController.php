<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\BuildingRequest;

use App\Models\Building;

class BuildingController extends Controller
{
  public function index(BuildingRequest $request)
  {
    $status = $request->input("status");

    $page = $request->input("page", "1");
    $rows = $request->input("rows", "10");

    $search = $request->input("search");

    $data = Building::latest("updated_at")
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

  public function store(BuildingRequest $request)
  {
    $data = Building::create([
      "name" => $request->name,
    ]);

    return response()->saved("Building", $data);
  }

  public function update(BuildingRequest $request, $id)
  {
    $data = Building::find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    $data->update([
      "name" => $request->name,
    ]);

    return response()->updated("Building", $data);
  }

  public function destroy($id)
  {
    $data = Building::withTrashed()->find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    if ($data->deleted_at) {
      $data->restore();

      return response()->restored("Building", $data);
    }

    $data->delete();

    return response()->archived("Building", $data);
  }
}
