<?php

use Newnet\Contact\Http\Controllers\Web\ContactController;
use Newnet\Contact\Http\Controllers\Web\NewsletterController;

Route::prefix(LaravelLocalization::setLocale())
->middleware(['localizationRedirect'])
->group( function () {
    Route::prefix('contact')->group(function () {
        Route::get('', [ContactController::class, 'index'])->name('contact.web.contact.index');
        Route::post('sendmail', [ContactController::class, 'sendMail'])->name('contact.web.contact.send-mail');

        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('contact.web.newsletter.index');
        Route::post('/newsletter', [NewsletterController::class, 'newsletter'])->name('contact.web.newsletter.post');


        /**
         * AJAX
         */
        Route::post('/newsletter-ajax', [NewsletterController::class, 'ajaxNewsletter'])->name('contact.web.newsletter.ajax.post');
    });

});
