<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Cms\Http\Resources\StoryItemsResource;
use Newnet\Cms\Http\Resources\StoryResource;
use Newnet\Cms\Models\Story;

class StoryController extends Controller
{
  /**
   * Get all stories
   */
  public function getStories()
  {
    $stories = Story::whereIsActive(true)->orderBy('id', 'desc')->paginate(20);
    return StoryResource::collection($stories ?? []);
  }

  /**
   * Get story items by slug
   */
  public function getStoryBySlug($slug)
  {
    $story = Story::whereSlug($slug)->whereIsActive(true)->first();
    if ($story) {
      return response()->json([
        'story' => new StoryResource($story),
        'story_items' => StoryItemsResource::collection($story->storyItems ?? [])
      ]);
    }
    return response()->json([
      'message' => 'Story not found',
    ]);
  }
}
