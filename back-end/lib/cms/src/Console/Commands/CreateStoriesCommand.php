<?php

namespace Newnet\Cms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Newnet\Cms\Models\Post;
use Newnet\Acl\Models\Admin;
use Newnet\Cms\Actions\CreateStoryFromPostAction;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Cms\Exceptions\CreateStoryException;

class CreateStoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:create-stories';

    /**
     * The console create stories from posts.
     *
     * @var string
     */
    protected $description = 'Create stories from posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $exceptCategory = setting('story_is_auto_create_from');
        try {
            DB::beginTransaction();
            $this->info('Starting create stories from posts');
            if ($exceptCategory && count($exceptCategory) > 0) {
                $posts = Post::whereHas('categories', function ($query) use ($exceptCategory) {
                    $query->whereNotIn('id', $exceptCategory);
                })->where(['is_created_story' => false])->orderBy('id', 'desc')->get();
            } else {
                $posts = Post::where(['is_created_story' => false])->orderBy('id', 'desc')->get();
            }
            foreach ($posts as $post) {
                CreateStoryFromPostAction::createStory($post);
            }
            DB::commit();
            $this->info('Finished creating stories from posts with ' . count($posts) . ' stories');
            event(new StoryEvent('created'));
            return [
                'status' => 'success',
                'message' => 'Finished creating stories from posts with ' . count($posts) . ' stories'
            ];
        } catch (CreateStoryException $ex) {
            logger('Error creating stories from posts', [$ex->getMessage()]);
            $this->info('Error creating stories from posts ' . $ex->getMessage());
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Error while creating the stories from posts '. $ex->getMessage(),
            ];
        }
    }
}
