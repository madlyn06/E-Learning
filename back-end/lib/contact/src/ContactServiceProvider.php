<?php

namespace Newnet\Contact;

use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Newnet\Contact\Events\SendEmailContactEvent;
use Newnet\Contact\Listeners\SendEmailContactListener;
use Newnet\Contact\Models\Label;
use Newnet\Contact\Models\Newsletter;
use Newnet\Contact\Repositories\Eloquent\LabelRepository;
use Newnet\Contact\Repositories\Eloquent\NewsletterRepository;
use Newnet\Contact\Repositories\LabelRepositoryInterface;
use Newnet\Contact\Repositories\NewsletterRepositoryInterface;
use Newnet\Acl\Facades\Permission;
use Newnet\Module\Support\BaseModuleServiceProvider;
use Newnet\Contact\Repositories\Eloquent\ContactRepository;
use Newnet\Contact\Repositories\ContactRepositoryInterface;
use Newnet\Contact\Models\Contact;

class ContactServiceProvider extends BaseModuleServiceProvider
{

    public function register()
    {
        parent::register();
        Event::listen(SendEmailContactEvent::class, SendEmailContactListener::class);

        $this->app->singleton(ContactRepositoryInterface::class, function () {
            return new ContactRepository(new Contact());
        });
        $this->app->singleton(NewsletterRepositoryInterface::class, function () {
            return new NewsletterRepository(new Newsletter());
        });
        $this->app->singleton(LabelRepositoryInterface::class, function () {
            return new LabelRepository(new Label());
        });
        require_once __DIR__.'/../helpers/helpers.php';
    }

    public function registerPermissions()
    {
        Permission::add('contact.admin.contact.index', __('contact::permission.contact.index'));
        Permission::add('contact.admin.contact.create', __('contact::permission.contact.create'));
        Permission::add('contact.admin.contact.edit', __('contact::permission.contact.edit'));
        Permission::add('contact.admin.contact.destroy', __('contact::permission.contact.destroy'));

        Permission::add('contact.admin.label.index', __('contact::permission.label.index'));
        Permission::add('contact.admin.label.create', __('contact::permission.label.create'));
        Permission::add('contact.admin.label.edit', __('contact::permission.label.edit'));
        Permission::add('contact.admin.label.destroy', __('contact::permission.label.destroy'));

        /**
         * Permission for newletter
         */
        Permission::add('contact.admin.newsletter.index', __('contact::permission.contact.index'));

    }

}
