<?php

use Illuminate\Support\Facades\Route;
use Modules\Manage\Http\Controllers\Api\ContactController;
use Modules\Manage\Http\Controllers\Api\DownloadController;
use Modules\Manage\Http\Controllers\Api\ManageController;
use Modules\Manage\Http\Controllers\Api\SeoController;

Route::get('all-services', [ManageController::class, 'getAllServices'])->name('manage.api.manage.getAllServices');
Route::get('services', [ManageController::class, 'getServices'])->name('manage.api.manage.getServices');
Route::get('services/{slug}', [ManageController::class, 'getServiceBySlug'])->name('manage.api.manage.getServiceBySlug');
Route::get('clients', [ManageController::class, 'getClients'])->name('manage.api.manage.getClients');
Route::get('brands', [ManageController::class, 'getBrands'])->name('manage.api.manage.getBrands');
Route::get('members', [ManageController::class, 'getMembers'])->name('manage.api.manage.getMembers');
Route::get('admins', [ManageController::class, 'getAdminMembers'])->name('manage.api.manage.getAdminMembers');
Route::get('admins/{slug}', [ManageController::class, 'getAdminDetail'])->name('manage.api.manage.getAdminDetail');

Route::get('random-posts', [ManageController::class, 'getRandomPosts'])->name('manage.api.manage.getRandomPosts');
Route::get('random-categories', [ManageController::class, 'getRandomCategory'])->name('manage.api.manage.getRandomCategory');
Route::get('faqs', [ManageController::class, 'getFaqs'])->name('api.faq.getFaqs');

Route::post('/contact', [ContactController::class, 'contact'])->name('manage.api.contact.contact');
Route::post('/subcribe', [ContactController::class, 'subcribe'])->name('manage.api.contact.subcribe');

Route::get('manage/seo/{key}', [SeoController::class, 'getMetaData'])->name('manage.api.seo.getMetadata');

Route::get('manage/pages', [ManageController::class, 'getAllPages'])->name('manage.api.manage.getAllPages');
Route::get('manage/pages/{slug}', [ManageController::class, 'getPageDetail'])->name('manage.api.manage.getPageDetail');

Route::get('portfolio-categories', [ManageController::class, 'getAllPortfolioCategories'])->name('manage.api.manage.getAllPortfolioCategories');
Route::get('portfolio-projects', [ManageController::class, 'getAllPortfolioProjects'])->name('manage.api.manage.getAllPortfolioProjects');

Route::get('manage/documents', [DownloadController::class, 'getAllDocuments'])->name('manage.api.download.getAllDocuments');
Route::get('manage/get-documents-in-category/{categoryId}', [DownloadController::class, 'getDocumentsInCategory'])->name('manage.api.download.getDocumentsInCategory');
Route::get('manage/document/related/{fileId}', [DownloadController::class, 'getRelatedFiles'])->name('manage.api.download.getRelatedFiles');
Route::get('manage/document/{slug}', [DownloadController::class, 'getDocumentDetail'])->name('manage.api.download.getDocumentDetail');
Route::post('manage/document/check-code', [DownloadController::class, 'checkCodeDownload'])->name('manage.api.download.checkCodeDownload');
