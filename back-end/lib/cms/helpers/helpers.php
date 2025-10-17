<?php

use Illuminate\Support\Facades\Lang;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Page;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\CategoryRepositoryInterface;
use Newnet\Cms\Repositories\PostRepositoryInterface;
use Illuminate\Support\Str;
use Newnet\Cms\Enums\ActionEnum;
use Newnet\Cms\Enums\PostActionEnum;
use Newnet\Cms\Models\Satellite;

if (!function_exists('get_page_layout_options')) {
    /**
     * Get Page Layout Options
     *
     * @return array
     */
    function get_page_layout_options()
    {
        return PageLayout::toOptions();
    }
}

if (!function_exists('get_page_layout_label')) {
    /**
     * Get Page Layout Label
     *
     * @param $key
     * @return string
     */
    function get_page_layout_label($key)
    {
        return PageLayout::getLabel($key);
    }
}

if (!function_exists('get_category_parent_options')) {
    /**
     * Get Category Parent Options
     *
     * @return array
     */
    function get_category_parent_options()
    {
        $options = [];

        $categoryTreeList = Category::defaultOrder()->withDepth()->get()->toFlatTree();
        foreach ($categoryTreeList as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim(str_pad('', $item->depth * 3, '-')).' '.$item->name,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_page_parent_options')) {
    /**
     * Get Page Parent Options
     *
     * @return array
     */
    function get_page_parent_options()
    {
        $options = [];

        $pageTreeList = Page::defaultOrder()->withDepth()->get()->toFlatTree();
        foreach ($pageTreeList as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim(str_pad('', $item->depth * 3, '-')).' '.$item->name,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_page_menu_builder_options')) {
    function get_page_menu_builder_options()
    {
        return get_page_parent_options();
    }
}

if (!function_exists('get_cms_category_menu_builder_options')) {
    function get_cms_category_menu_builder_options()
    {
        return get_category_parent_options();
    }
}

if (!function_exists('get_cms_setting_page_options')) {
    function get_cms_setting_page_options()
    {
        return get_page_parent_options();
    }
}

if (!function_exists('get_cms_last_post')) {
    /**
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    function get_cms_last_post($limit = 20)
    {
        return app(PostRepositoryInterface::class)->lastPost($limit);
    }
}

if (!function_exists('get_cms_author')) {
    /**
     * @param $item
     * @return \Newnet\Acl\Models\Admin
     */
    function get_cms_author($item)
    {
        return object_get($item, 'author');
    }
}

if (!function_exists('get_cms_read_time_seconds')) {
    function get_cms_read_time_seconds($content)
    {
        if ($content instanceof Post) {
            $content = $content->content;
        }

        $wordnum = str_word_count(strip_tags($content));

        $avgtime = 120;

        return floor((int)$wordnum / $avgtime) * 60;
    }
}

if (!function_exists('get_cms_read_time')) {
    function get_cms_read_time($content)
    {
        $seconds = get_cms_read_time_seconds($content);

        $minutes = floor($seconds / 60);

        if ($minutes < 1) {
            return __('less than 1 minute');
        } else {
            return trans_choice(':min minute|:min minutes', $minutes, [
                'min' => $minutes
            ]);
        }
    }
}

if (!function_exists('get_cms_categories_root')) {
    function get_cms_categories_root()
    {
        return app(CategoryRepositoryInterface::class)->listRoot();
    }
}

if (!function_exists('get_cms_count_posts')) {
    function get_cms_count_posts()
    {
        return app(PostRepositoryInterface::class)->count();
    }
}

if (!function_exists('get_cms_count_posts_in_category')) {
    function get_cms_count_posts_in_category(Category $category)
    {
        return app(PostRepositoryInterface::class)->countInCategory($category);
    }
}

if (!function_exists('get_cms_sticky_post')) {
    function get_cms_sticky_post($limit)
    {
        return app(PostRepositoryInterface::class)->stickyPost($limit);
    }
}

if (!function_exists('get_cms_related_posts')) {
    function get_cms_related_posts(Post $post, $limit = 10)
    {
        return app(PostRepositoryInterface::class)->relatedPosts($post, $limit);
    }
}

if (!function_exists('get_story_layout_options'))
{
    function get_story_layout_options()
    {
        $options = [];

        $layouts = config('cms.cms.story.layouts');
        foreach ($layouts as $layout) {
            $options[] = [
                'value' => $layout,
                'label' => get_story_layout_label($layout),
            ];
        }

        return $options;
    }
}


if (!function_exists('get_story_layout_label'))
{
    function get_story_layout_label($layout)
    {
        $langKey = "slider::slider.layouts.{$layout}";

        return Lang::has($langKey) ? Lang::get($langKey) : Str::ucfirst($layout);
    }
}

if (!function_exists('get_story_text_point_options'))
{
    function get_story_text_point_options()
    {
        $options = [];
        $items = [
            __('cms::story.setting.pointer_post'),
            __('cms::story.setting.pointer_home'),
        ];
        foreach ($items as $key => $item) {
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_story_position_in_post_options'))
{
    function get_story_position_in_post_options()
    {
        $options = [];
        $items = [
            __('cms::story.setting.insert.first'),
            __('cms::story.setting.insert.before_h2'),
            __('cms::story.setting.insert.last'),
        ];
        foreach ($items as $key => $item) {
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_all_posts_options')) {
    /**
     * Get Page Parent Options
     *
     * @return array
     */
    function get_all_posts_options()
    {
        $options = [];

        $posts = Post::whereIsCreatedStory(false)->orderBy('id', 'desc')->get();
        foreach ($posts as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => $item->name,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_chatgpt_model_options')) {
    /**
     * Get Page Parent Options
     *
     * @return array
     */
    function get_chatgpt_model_options()
    {
        $options = [];

        $models = [
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            'gpt-4' => 'GPT-4',
            'gpt-4o' => 'GPT-4o',
            'gpt-4o-mini' => 'GPT-4o Mini',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'o1-mini' => 'O1 Mini',
        ];
        foreach ($models as $key => $item) {
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_post_action_options'))
{
    function get_post_action_options()
    {
        $options = [];

        $actions = [
            PostActionEnum::DRAFT->value => PostActionEnum::getLabel(PostActionEnum::DRAFT->value),
            PostActionEnum::PUBLISH->value => PostActionEnum::getLabel(PostActionEnum::PUBLISH->value),
            PostActionEnum::SCHEDULE->value => PostActionEnum::getLabel(PostActionEnum::SCHEDULE->value),
        ];

        foreach ($actions as $key => $item) {
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_action_options'))
{
    function get_action_options()
    {
        $options = [];

        $actions = [
            ActionEnum::REWRITE->value => ActionEnum::getLabel(ActionEnum::REWRITE->value),
            ActionEnum::TRANSLATE->value => ActionEnum::getLabel(ActionEnum::TRANSLATE->value),
        ];

        foreach ($actions as $key => $item) {
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_another_keywords'))
{
    function get_another_keywords($except) {
        return \Newnet\Cms\Models\Keyword::whereNotIn('id', $except)->get();
    }
}

if (!function_exists('get_satellite_site_options')) {
    /**
     * Get Page Parent Options
     *
     * @return array
     */
    function get_satellite_site_options()
    {
        $options = [];

        $satellites = Satellite::whereIsActive(true)->get();
        foreach ($satellites as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => $item->name,
            ];
        }

        return $options;
    }
}
