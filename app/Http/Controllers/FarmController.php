<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\FarmRequest;

use App\Models\Farm;

class FarmController extends Controller
{
  public function index(FarmRequest $request)
  {
    $status = $request->input("status");

    $page = $request->input("page", "1");
    $rows = $request->input("rows", "10");

    $search = $request->input("search");

    $data = Farm::latest("updated_at")
      ->with("buildings")
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

  public function store(FarmRequest $request)
  {
    $data = Farm::create([
      "name" => $request->name,
    ]);

    $data->buildings()->attach($request->buildings);

    return response()->saved("Farm", $data);
  }

  public function update(FarmRequest $request, $id)
  {
    $data = Farm::find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    $data->update([
      "name" => $request->name,
    ]);

    $data->buildings()->sync($request->buildings);

    return response()->updated("Farm", $data);
  }

  public function destroy($id)
  {
    $data = Farm::withTrashed()->find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    if ($data->deleted_at) {
      $data->restore();

      return response()->restored("Farm", $data);
    }

    $data->delete();

    return response()->archived("Farm", $data);
  }
}
