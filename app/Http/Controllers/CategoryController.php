<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CategoryRequest;

use App\Models\Category;

class CategoryController extends Controller
{
  public function index(CategoryRequest $request)
  {
    $status = $request->input("status");

    $page = $request->input("page", "1");
    $rows = $request->input("rows", "10");

    $search = $request->input("search");

    $data = Category::latest("updated_at")
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

  public function store(CategoryRequest $request)
  {
    $data = Category::create([
      "name" => $request->name,
    ]);

    return response()->saved("Category", $data);
  }

  public function update(CategoryRequest $request, $id)
  {
    $data = Category::find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    $data->update([
      "name" => $request->name,
    ]);

    return response()->updated("Category", $data);
  }

  public function destroy($id)
  {
    $data = Category::withTrashed()->find($id);

    if (empty($data)) {
      return response()->doesntExist();
    }

    if ($data->deleted_at) {
      $data->restore();

      return response()->restored("Category", $data);
    }

    $data->delete();

    return response()->archived("Category", $data);
  }
}
