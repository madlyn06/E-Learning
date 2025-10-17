<?php

namespace Newnet\Cms\Listeners;

use Newnet\Cms\Repositories\PageRepositoryInterface;
use Newnet\Core\Events\NewnetInstalled;

class CreateHomePageListener
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Handle the event.
     *
     * @param  NewnetInstalled $event
     * @return void
     */
    public function handle(NewnetInstalled $event)
    {
        $this->pageRepository->create([
            'name' => [
                'en' => 'Home page',
                'vi' => 'Trang chủ',
            ],
            'page_layout' => 'home',
            'url' => 'home',
        ]);
    }
}
