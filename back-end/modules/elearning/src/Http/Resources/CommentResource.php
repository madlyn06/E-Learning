<?php
namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lesson_id' => $this->lesson_id,
            'content' => $this->content,
            'images' => $this->images,
            'parent_id' => $this->parent_id,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'is_spam' => $this->is_spam,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ] : null,
            'children' => CommentResource::collection($this->whenLoaded('children')),
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
