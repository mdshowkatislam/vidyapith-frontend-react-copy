<?php
namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    public function successResponse($data, $code = Response::HTTP_OK) {
        return response()->json(['status' => true, 'data' => $data], $code);
    }
    public function successResponseWithData($data, $message, $code = Response::HTTP_OK) {
        return response()->json(['status' => true, 'data' => $data, 'message' => $message], $code);
    }

    public function successResponsePaginate($data, $code = Response::HTTP_OK) {
        $totalPages = $data->lastPage() ?? 0;
        $currentPage = $data->currentPage() ?? 0;
        
        $start_page = max(1, $currentPage - 3);
        $end_page = min($totalPages, $start_page + 7);
    
        if ($end_page < 8) {
            $start_page = max(1, $totalPages - 7);
            $end_page = $totalPages;
        }

        $metaData = (object) [
            'next_page_url' => $data->nextPageUrl(),
            'prev_page_url' => $data->previousPageUrl(),
            'total_page_count' => $data->lastPage(),
            'page_url' => $data->path(),
            'has_more_pages' => $data->hasMorePages(),
            'current_page' =>$data->currentPage(),
            'total_items' => $data->total(),
            'links' => $this->pageLinks($data->getUrlRange($start_page, $end_page), $data->currentPage()),
        ];
        return response()->json(['status' => true, 
            'meta' => $metaData,
            'data' => $data->items()
        ], $code);
    }

    public function successMessage($message, $code = Response::HTTP_OK){
        return response()->json(['status' => true, 'message' => $message, 'code' => $code], $code);
    }

    public function errorResponse($message, $code){
        return response()->json(['status' => false, 'message' => $message, 'code' => $code], $code);
    }

    private function pageLinks($data = [], $current_page = 1) {
        $links = [];
        foreach( $data as  $key => $value) {
            $link = [
                'active' => $key == $current_page,
                'page' => $key,
                // 'url' => $value,
            ];
            array_push($links ,$link );
        }
        $array_object = json_decode(json_encode($links));
        return $array_object;
    }
}
