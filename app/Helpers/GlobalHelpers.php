<?php

function apiResponse($data = null, $message = '', $success = true, $status = 200, $meta = [])
{
    return response()->json([
        'success'   => $success,
        'message'   => $message,
        'data'      => $data,
        'meta'      => $meta,
    ], $status);
}

function getPaginationData($paginator)
{
    return [
        'total'         => $paginator->total(),
        'per_page'      => $paginator->perPage(),
        'current_page'  => $paginator->currentPage(),
        'last_page'     => $paginator->lastPage(),
        'from'          => $paginator->firstItem(),
        'to'            => $paginator->lastItem(),
    ];
}


