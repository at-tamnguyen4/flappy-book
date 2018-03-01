<?php

namespace App\Libraries\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Structure of json
     *
     * @param array $resonseArray array response
     * @param int   $code         response status
     *
     * @return Illuminate\Support\Collection
     */
    public function structJson($resonseArray, $code = 200)
    {
        $collection = collect([
            'meta' => [
                'status' => 'successfully',
                'code' => $code
            ],
            'data' => array_values($resonseArray['data']),
            'pagination' => [
                    'total' =>  $resonseArray['total'],
                    'per_page' =>  $resonseArray['per_page'],
                    'current_page' =>  $resonseArray['current_page'],
                    'total_pages' =>  $resonseArray['last_page'],
                    'links' => [
                       'prev' => $resonseArray['prev_page_url'],
                       'next' =>$resonseArray['next_page_url']
                    ]
            ]
        ]);

        return $collection;
    }
    /**
     * Response detail of data
     *
     * @param object $data instance
     * @param int    $code response status
     *
     * @return \Illuminate\Http\Response
     */
    protected function responseSuccess($data = [], $code = 200)
    {
        return response()->json([
            'meta' => [
                'status' => __('api.successfully'),
                'code' => $code
            ],
            'data' => $data
        ]);
    }

    /**
     * Response list data
     *
     * @param LengthAwarePaginator $responseData list resource
     * @param int                  $code         response status
     *
     * @return \Illuminate\Http\Response
     */
    protected function responsePaginate(LengthAwarePaginator $responseData, $code = 200)
    {
        return response()->json([
            'meta' => [
                'status' => __('api.successfully'),
                'code' => $code
            ],
            'data' => $responseData->toArray()['data'],
            'pagination' => [
                'total' =>  $responseData->total(),
                'per_page' =>  $responseData->perPage(),
                'current_page' =>  $responseData->currentPage(),
                'total_pages' =>  $responseData->lastPage(),
                'links' => [
                   'prev' => $responseData->previousPageUrl(),
                   'next' =>$responseData->nextPageUrl(),
                ]
            ],
        ]);
    }
}
