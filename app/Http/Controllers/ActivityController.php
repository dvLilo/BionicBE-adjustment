<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

use App\Http\Requests\ActivityRequest;

class ActivityController extends Controller
{
  public function index(ActivityRequest $request)
  {
    $page = $request["page"] ?? 1;

    $date_from = $request["from"] ?? null;
    $date_to = $request["to"] ?? null;

    $type = $request["type"] ?? null;
    $event = $request["event"] ?? null;

    $name = $request["name"] ?? null;

    $data = Activity::with(["causer", "subject"])
      ->where("log_name", $type)
      ->whereBetween("created_at", [$date_from, $date_to])

      ->when($event, function ($query) use ($event) {
        return $query->where("event", $event);
      })

      ->when($name, function ($query) use ($name) {
        return $query->whereHas("causer", function ($query) use ($name) {
          return $query->where("full_name", "like", "%" . $name . "%");
        });
      })
      ->latest()
      ->when($page == "none", fn($query) => $query->get(), fn($query) => $query->paginate());

    if ($data->isEmpty()) {
      return response()->doesntExist();
    }

    return $data;
  }
}
