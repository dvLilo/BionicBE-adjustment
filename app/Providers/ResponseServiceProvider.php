<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    Response::macro("updated", function ($model, $data = [], $status = 200) {
      return Response::json(
        [
          "status" => $status,
          "message" => $model . " has been updated.",
          "result" => $data,
        ],
        $status
      );
    });

    Response::macro("deleted", function ($model, $data = [], $status = 200) {
      return Response::json(
        [
          "status" => $status,
          "message" => $model . " has been deleted.",
          "result" => $data,
        ],
        $status
      );
    });

    Response::macro("doesntExist", function ($status = 404) {
      return Response::json(
        [
          "status" => $status,
          "message" => "No records found.",
        ],
        $status
      );
    });

    Response::macro("doesntDuplicate", function ($status = 403) {
      return Response::json(
        [
          "status" => $status,
          "message" => "Cannot delete transaction that doesn't have duplicate.",
        ],
        $status
      );
    });

    Response::macro("login", function ($data = [], $status = 200) {
      return Response::json(
        [
          "status" => $status,
          "message" => "Signed-in successfully.",
          "result" => $data,
        ],
        $status
      );
    });

    Response::macro("logout", function ($data = [], $status = 200) {
      return Response::json(
        [
          "status" => $status,
          "message" => "Signed-out successfully.",
          "result" => $data,
        ],
        $status
      );
    });
  }
}
