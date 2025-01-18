<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{
  public function apiResponse($data, $meta = [])
  {
    $metaData = [
      "code"    => Response::HTTP_OK,
      "message" => "",
      "include" => [],
      "debug"   => new \stdClass(),
      "trace"   => new \stdClass()
    ];

    // add meta value
    if (!empty($meta)) {
      foreach ($meta as $key => $value) {
        if (isset($metaData[$key]))
          $metaData[$key] = $value;
      }
    }

    // empty data
    if (empty($data) || $data == new \stdClass()) {
      return [
        'data' => $data,
        'meta' => $metaData
      ];
    }

    // add meta pagination key
    $pagination = isset($data->collection) ? $this->getPaginate($data) : false;
    if ($pagination) {
      $metaData['pagination'] = $pagination;
    }

    return [
      'data' => $data,
      'meta' => $metaData
    ];
  }

  public function apiResponseError($data, $meta = [])
  {
    $metaData = [
      "code"    => Response::HTTP_OK,
      "message" => "",
      "errors"  => [],
      "debug"   => new \stdClass(),
      "trace"   => new \stdClass()
    ];

    // add meta value
    if (!empty($meta)) {
      foreach ($meta as $key => $value) {
        if (isset($metaData[$key]))
          $metaData[$key] = $value;
      }
    }

    // empty data
    if (empty($data) || $data == new \stdClass()) {
      return [
        'data' => $data,
        'meta' => $metaData
      ];
    }

    return [
      'data' => $data,
      'meta' => $metaData
    ];
  }

  public function errorResponse($error = null, $code = Response::HTTP_NOT_FOUND)
  {
    $errorMessage = $error;
    $errorData = [];
    if (!empty($error) && !is_string($error)) {
      $errorMessages = is_array($error) ? array_values($error) : array_values($error->toArray());
      $errorMessage = $errorMessages[0];
      $errorMessage = is_array($errorMessage) ? $errorMessage[0] : $errorMessage;
      $errorData = $error;
    }

    $response['data'] = null;
    $response['meta']['code'] = $code;
    $response['meta']['message'] = $errorMessage;
    $response['meta']['errors'] = $errorData;
    if (env('RETURN_ERROR_DEBUG_TRACE'))
      $response['meta']['trace'] = json_encode($error);

    return response()->json($response, $code);
  }

  // Private function =======================================

  private function getPaginate($data)
  {
    if (isset($data->resource)) {
      $resource = !is_array($data->resource) ? $data->resource->toArray() : $data->resource;
      if (isset($resource['current_page'])) {
        return [
          "current" => $resource['current_page'],
          "last"    => $resource['last_page'],
          "per"     => (int)$resource['per_page'],
          "total"   => $resource['total'],
          "count"   => count($resource['data']),
          "from"    => $resource['from'],
          "to"      => $resource['to']
        ];
      }
    }

    return null;
  }

  public function apiResponseErrorValidater($validator)
  {
      $msg = $validator->errors()->first();
      $includes = [];
      foreach($validator->errors()->getMessages() as $key => $val){
          $includes[$key] = isset($val[0])?$val[0]:$val;
      }
      $meta["code"] = 400;
      $meta["message"] = $msg; 
      $meta["errors"] = $includes; 
      return $this->apiResponseError([], $meta);
  }
}
