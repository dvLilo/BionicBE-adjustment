<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

use App\Http\Requests\ActivityRequest;

class ActivityController extends Controller
{
  public function index(ActivityRequest $request)
  {
    $type = $request["type"] ?? null;
    $event = $request["event"] ?? null;

    $name = $request["name"] ?? null;
    $date = $request["date"] ?? null;

    $data = Activity::with(["causer", "subject"])
      ->when($type, function ($query) use ($type) {
        return $query->where("log_name", $type);
      })
      ->when($event, function ($query) use ($event) {
        return $query->where("event", $event);
      })
      ->when($date, function ($query) use ($date) {
        return $query->whereDate("created_at", $date);
      })
      ->when($name, function ($query) use ($name) {
        return $query->whereHas("causer", function ($query) use ($name) {
          return $query->where("full_name", "like", "%" . $name . "%");
        });
      })
      ->paginate();

    if ($data->isEmpty()) {
      return response()->doesntExist();
    }

    return $data;
  }
}
