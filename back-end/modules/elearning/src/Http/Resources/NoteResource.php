<?php
namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lesson_id' => $this->lesson_id,
            'content' => $this->content,
            'time_iso' => $this->time_iso,
            'time_seconds' => $this->time_seconds,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        
        if (is_object($resource) && method_exists($resource, 'links')) {
            $collection->additional(['meta' => [
                'current_page' => $resource->currentPage(),
                'last_page' => $resource->lastPage(),
                'per_page' => $resource->perPage(),
                'total' => $resource->total(),
            ]]);
        }
        
        return $collection;
    }
}
