<?php

namespace Newnet\Cms;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Newnet\Cms\Actions\DocumentationRenderAction;
use Newnet\Cms\Actions\HandleContentAction;
use Newnet\Cms\Actions\StoryRenderAction;
use Newnet\Cms\Console\Commands\CreateStoriesCommand;
use Newnet\Cms\Console\Commands\DatabaseBackup;
use Newnet\Cms\Console\Commands\InstallCron;
use Newnet\Cms\Console\Commands\PublishScheduledPosts;
use Newnet\Cms\Console\Commands\SyncDataFromWordpressCommand;
use Newnet\Cms\Console\Commands\SyncToSatelliteSiteCommand;
use Newnet\Cms\Console\Commands\UpdateInternalLinkCommand;
use Newnet\Cms\Console\Commands\UpdatePostsWeekly;
use Newnet\Cms\Events\CategoryEvent;
use Newnet\Cms\Events\ContentListableEvent;
use Newnet\Cms\Events\CrawledPostEvent;
use Newnet\Cms\Events\DeletedStoryEvent;
use Newnet\Cms\Events\NewItemEvent;
use Newnet\Cms\Events\PageEvent;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Events\RunningSyncDataEvent;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Cms\Events\StorySettingEvent;
use Newnet\Cms\Events\SatelliteSyncEvent;
use Newnet\Cms\Exporter\DownloadExporter;
use Newnet\Cms\Exporter\ExportManager;
use Newnet\Cms\Exporter\OrderExporter;
use Newnet\Cms\Exporter\PostCategoryExporter;
use Newnet\Cms\Exporter\PostExporter;
use Newnet\Cms\Exporter\ProductCategoryExporter;
use Newnet\Cms\Exporter\ProductExporter;
use Newnet\Cms\Facades\DocumentationRender;
use Newnet\Cms\Facades\PageLayout;
use Newnet\Cms\Interface\ContentInterface;
use Newnet\Cms\Listeners\CategoryListener;
use Newnet\Cms\Listeners\ContentListableListener;
use Newnet\Cms\Listeners\CrawledPostListener;
use Newnet\Cms\Listeners\PageListener;
use Newnet\Cms\Listeners\PostListener;
use Newnet\Cms\Listeners\CreateHomePageListener;
use Newnet\Cms\Listeners\DeletedStoryListener;
use Newnet\Cms\Listeners\NewItemListener;
use Newnet\Cms\Listeners\RunningSyncDataListener;
use Newnet\Cms\Listeners\StoryListener;
use Newnet\Cms\Listeners\StorySettingListener;
use Newnet\Cms\Listeners\SatelliteSyncListener;
use Newnet\Cms\MenuBuilders\CategoryMenuBuilder;
use Newnet\Cms\MenuBuilders\PageMenuBuilder;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Models\CrawlHistoryItem;
use Newnet\Cms\Models\FAQ;
use Newnet\Cms\Models\Keyword;
use Newnet\Cms\Models\Page;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Models\Satellite;
use Newnet\Cms\Models\Story;
use Newnet\Cms\Models\StoryItem;
use Newnet\Cms\Models\StorySetting;
use Newnet\Cms\Repositories\CategoryRepositoryInterface;
use Newnet\Cms\Repositories\CrawlHistoryItemRepositoryInterface;
use Newnet\Cms\Repositories\CrawlHistoryRepositoryInterface;
use Newnet\Cms\Repositories\Eloquent\CategoryRepository;
use Newnet\Cms\Repositories\Eloquent\CrawlHistoryItemRepository;
use Newnet\Cms\Repositories\Eloquent\CrawlHistoryRepository;
use Newnet\Cms\Repositories\Eloquent\FaqRepository;
use Newnet\Cms\Repositories\Eloquent\KeywordRepository;
use Newnet\Cms\Repositories\Eloquent\PageRepository;
use Newnet\Cms\Repositories\Eloquent\PostRepository;
use Newnet\Cms\Repositories\Eloquent\SatelliteRepository;
use Newnet\Cms\Repositories\Eloquent\StoryItemRepository;
use Newnet\Cms\Repositories\Eloquent\StoryRepository;
use Newnet\Cms\Repositories\Eloquent\StorySettingRepository;
use Newnet\Cms\Repositories\FaqRepositoryInterface;
use Newnet\Cms\Repositories\KeywordRepositoryInterface;
use Newnet\Cms\Repositories\PageRepositoryInterface;
use Newnet\Cms\Repositories\PostRepositoryInterface;
use Newnet\Cms\Repositories\SatelliteRepositoryInterface;
use Newnet\Cms\Repositories\StoryItemRepositoryInterface;
use Newnet\Cms\Repositories\StoryRepositoryInterface;
use Newnet\Cms\Repositories\StorySettingRepositoryInterface;
use Newnet\Core\Events\NewnetInstalled;
use Newnet\Module\Support\BaseModuleServiceProvider;

class CmsServiceProvider extends BaseModuleServiceProvider
{
    public function getModuleNamespace()
    {
        return 'cms';
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(PageRepositoryInterface::class, function () {
            return new PageRepository(new Page());
        });

        $this->app->singleton(PostRepositoryInterface::class, function () {
            return new PostRepository(new Post());
        });

        $this->app->singleton(CategoryRepositoryInterface::class, function () {
            return new CategoryRepository(new Category());
        });

        $this->app->singleton(StoryRepositoryInterface::class, function () {
            return new StoryRepository(new Story());
        });

        $this->app->singleton(StoryItemRepositoryInterface::class, function () {
            return new StoryItemRepository(new StoryItem());
        });

        $this->app->singleton(StorySettingRepositoryInterface::class, function () {
            return new StorySettingRepository(new StorySetting());
        });

        $this->app->singleton(CrawlHistoryRepositoryInterface::class, function () {
            return new CrawlHistoryRepository(new CrawlHistory());
        });

        $this->app->singleton(CrawlHistoryItemRepositoryInterface::class, function () {
            return new CrawlHistoryItemRepository(new CrawlHistoryItem());
        });

        $this->app->singleton(KeywordRepositoryInterface::class, function () {
            return new KeywordRepository(new Keyword());
        });

        $this->app->singleton(SatelliteRepositoryInterface::class, function () {
            return new SatelliteRepository(new Satellite());
        });

        $this->app->singleton(FaqRepositoryInterface::class, function () {
            return new FaqRepository(new FAQ());
        });

        $this->app->singleton('module.cms.page-layout', function () {
            return new PageLayoutGroup();
        });

        $this->app->singleton(ExportManager::class, function () {
            $manager = new ExportManager();

            $manager->registerStrategy('post', new PostExporter());
            $manager->registerStrategy('post-category', new PostCategoryExporter());
            $manager->registerStrategy('product', new ProductExporter());
            $manager->registerStrategy('product-category', new ProductCategoryExporter());
            $manager->registerStrategy('order', new OrderExporter());
            $manager->registerStrategy('download', new DownloadExporter());

            return $manager;
        });

        $this->app->singleton('story.render', StoryRenderAction::class);

        $this->app->singleton('module.cms.documentation-render', DocumentationRenderAction::class);

        AliasLoader::getInstance()->alias('PageLayout', PageLayout::class);
        AliasLoader::getInstance()->alias('DocumentationRender', DocumentationRender::class);

        $this->app->bind(ContentInterface::class, HandleContentAction::class);

        require_once __DIR__.'/../helpers/helpers.php';
    }

    public function boot()
    {
        parent::boot();

        Event::listen(NewnetInstalled::class, CreateHomePageListener::class);
        Event::listen(CategoryEvent::class, CategoryListener::class);
        Event::listen(NewItemEvent::class, NewItemListener::class);
        Event::listen(PostEvent::class, PostListener::class);
        Event::listen(PageEvent::class, PageListener::class);
        Event::listen(StorySettingEvent::class, StorySettingListener::class);
        Event::listen(StoryEvent::class, StoryListener::class);
        Event::listen(DeletedStoryEvent::class, DeletedStoryListener::class);
        Event::listen(RunningSyncDataEvent::class, RunningSyncDataListener::class);
        Event::listen(ContentListableEvent::class, ContentListableListener::class);
        Event::listen(CrawledPostEvent::class, CrawledPostListener::class);
        Event::listen(SatelliteSyncEvent::class, SatelliteSyncListener::class);

        $this->commands([
            CreateStoriesCommand::class,
            SyncDataFromWordpressCommand::class,
            DatabaseBackup::class,
            UpdateInternalLinkCommand::class,
            UpdatePostsWeekly::class,
            PublishScheduledPosts::class,
            InstallCron::class,
            SyncToSatelliteSiteCommand::class,
        ]);

        $this->registerDocumentation();
    }

    public function registerFrontendMenuBuilders()
    {
        \FrontendMenuBuilder::add(PageMenuBuilder::class);
        \FrontendMenuBuilder::add(CategoryMenuBuilder::class);
    }

    protected function registerDocumentation()
    {
        DocumentationRender::add('documentation/media', 'media', 'Media', 'media::admin.docs.index');
        DocumentationRender::add('documentation/acl', 'acl', 'Acl', 'acl::admin.docs.index');
        DocumentationRender::add('documentation/seo', 'seo', 'SEO', 'seo::admin.docs.index');
    }
}
