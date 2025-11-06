<?php

use Illuminate\Support\Facades\Route;
use Newnet\Contact\Http\Controllers\Api\ContactController;
use Newnet\Contact\Http\Controllers\Api\NewsletterController;

// Contact form
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// Newsletter
Route::post('/contact/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/contact/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
