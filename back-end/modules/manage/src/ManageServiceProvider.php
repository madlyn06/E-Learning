<?php

namespace Modules\Manage;

use Illuminate\Support\Facades\Event;
use Modules\Manage\Events\ClientEvent;
use Modules\Manage\Events\FAQEvent;
use Modules\Manage\Events\PageEvent;
use Modules\Manage\Events\TeamEvent;
use Modules\Manage\Listeners\ClientListener;
use Modules\Manage\Listeners\FAQListener;
use Modules\Manage\Listeners\PageListener;
use Modules\Manage\Listeners\TeamListener;
use Newnet\Cms\Facades\DocumentationRender;
use Newnet\Module\Support\BaseModuleServiceProvider;

class ManageServiceProvider extends BaseModuleServiceProvider
{
  public function register()
  {
    parent::register();
    require_once __DIR__.'/../helpers/helper.php';
  }

  public function boot()
  {
    parent::boot();
    Event::listen(ClientEvent::class, ClientListener::class);
    Event::listen(FAQEvent::class, FAQListener::class);
    Event::listen(TeamEvent::class, TeamListener::class);
    Event::listen(PageEvent::class, PageListener::class);

    DocumentationRender::add('documentation/homepage', 'homepage', 'Trang chủ', 'manage::admin.docs.homepage');
    DocumentationRender::add('documentation/manage-team', 'manage-team', 'Quản lý team', 'manage::admin.docs.team');
    DocumentationRender::add('documentation/manage-services', 'manage-services', 'Quản lý dịch vụ', 'manage::admin.docs.services');
    DocumentationRender::add('documentation/manage-faqs', 'manage-faqs', 'Quản lý Q&A', 'manage::admin.docs.faqs');
    DocumentationRender::add('documentation/manage-clients', 'manage-clients', 'Quản lý khách hàng', 'manage::admin.docs.clients');
    DocumentationRender::add('documentation/manage-contact', 'manage-contact', 'Quản lý liên hệ', 'manage::admin.docs.contact');
    DocumentationRender::add('documentation/manage-subcribe', 'manage-subcribe', 'Quản lý đăng ký website', 'manage::admin.docs.subcribe');
    DocumentationRender::add('documentation/manage-seo', 'manage-seo', 'Quản lý SEO', 'manage::admin.docs.seo');
    DocumentationRender::add('documentation/manage-banner', 'manage-banner', 'Quản lý banner', 'manage::admin.docs.banner');
    DocumentationRender::add('documentation/manage-reason', 'manage-reason', 'Quản lý lý do lựa chọn chúng tôi', 'manage::admin.docs.reason');
  }
}
