<?php

namespace Newnet\Cms\Actions;

use Illuminate\Support\Facades\Log;
use Newnet\Cms\Models\Story;
use Newnet\Cms\Repositories\StoryItemRepositoryInterface;
use Newnet\Cms\Repositories\StoryRepositoryInterface;
use Newnet\Cms\Repositories\Eloquent\StoryItemRepository;
use Newnet\Cms\Repositories\Eloquent\StoryRepository;
use Newnet\Cms\Utils\StringUtils;

class StoryRenderAction
{
    /**
     * @var StoryRepositoryInterface|StoryRepository
     */
    protected $storyRepository;

    /**
     * @var StoryItemRepositoryInterface|StoryItemRepository
     */
    protected $storyItemRepository;

    public function __construct(
        StoryRepositoryInterface $storyRepository,
        StoryItemRepositoryInterface $storyItemRepository
    ) {
        $this->storyRepository = $storyRepository;
        $this->storyItemRepository = $storyItemRepository;
    }

    public function render($storyKey, $layout = null)
    {
        try {
            $story = $this->findStory($storyKey);
        } catch (\Exception $e) {
            Log::error("Story <strong>{$storyKey}</strong> not found!");
            return '';
        }
        if (!$storyKey->is_active) {
            return '';
        }
        $layout = $layout ?: 'Story::layouts.' . $story->layout;

        $storyItems = $this->storyItemRepository->allActiveOfStory($story->id);

        return view($layout)->with([
            'Story'      => $story,
            'StoryItems' => $storyItems,
        ]);
    }

    protected function findStory($storyKey)
    {
        if (is_numeric($storyKey)) {
            $story = $this->storyRepository->find($storyKey);
        } else {
            $story = $this->storyRepository->findBySlug($storyKey);
        }
        return $story;
    }

    public static function renderContent($content)
    {
        $storyCodes = StringUtils::convertContentShortCode($content);
        $stories = Story::whereIn('slug', $storyCodes['code'])->get();
        foreach ($stories as $key => $story) {
            $storyHtml = view('cms::api.story.detail', compact('story'))->render();
            $pattern = '[story_code="' . $storyCodes['code'][$key] . '"]';
            $itemContent = str_replace($pattern, $storyHtml, $content);
            $content = $itemContent;
        }
        return $content;
    }
}
