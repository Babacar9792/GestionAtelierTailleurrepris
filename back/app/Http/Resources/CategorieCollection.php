<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategorieCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            // 'data' => CategorieResource::collection($this->collection),
            'data' => $this->collection,
            'message' => '',
            'status' => true,
            // 'links' => $this->links
        ];
    }

    
    public function paginationInformation($request, $paginated, $default) : array
    {
        return ["links" => $default["meta"]["links"]];  

    }

    // public function withMessage($message)
    // {
    //     return $this->additional([
    //         'message' => $message
    //     ]);

    // }
}
