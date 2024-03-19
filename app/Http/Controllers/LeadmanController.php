<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\LeadmanRequest;

use App\Models\Leadman;

class LeadmanController extends Controller
{
  public function index(LeadmanRequest $request)
  {
    $status = $request->input("status");

    $page = $request->input("page", "1");
    $rows = $request->input("rows", "10");

    $search = $request->input("search");

    $data = Leadman::latest("updated_at")
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

  public function store(LeadmanRequest $request)
  {
    $data = Leadman::create([
      "name" => $request->name,
    ]);

    return response()->saved("Leadman", $data);
  }

  public function update(LeadmanRequest $request, $id)
  {
    $data = Leadman::find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    $data->update([
      "name" => $request->name,
    ]);

    return response()->updated("Leadman", $data);
  }

  public function destroy($id)
  {
    $data = Leadman::withTrashed()->find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    if ($data->deleted_at) {
      $data->restore();

      return response()->restored("Leadman", $data);
    }

    $data->delete();

    return response()->archived("Leadman", $data);
  }
}
