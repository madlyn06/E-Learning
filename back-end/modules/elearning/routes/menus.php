<?php

use Modules\Elearning\ElearningAdminMenuKey;
use Newnet\AdminUi\Facades\AdminMenu;

AdminMenu::addItem(__('elearning::module.module_name'), [
    'id' => ElearningAdminMenuKey::ELEARNING,
    'icon' => 'fas fa-list',
    'order' => 1000,
]);

// Category Management
AdminMenu::addItem(__('elearning::category.model_name'), [
    'id'     => ElearningAdminMenuKey::CATEGORY,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.categories.index',
    'icon'   => 'fas fa-tags',
    'order'  => 1,
]);

AdminMenu::addItem(__('elearning::course.model_name'), [
    'id'     => ElearningAdminMenuKey::COURSE,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.courses.index',
    'icon'   => 'fas fa-cube',
    'order'  => 2,
]);

// Enrollments
AdminMenu::addItem(__('elearning::enrollment.model_name'), [
    'id'     => ElearningAdminMenuKey::ENROLLMENT,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.enrollments.index',
    'icon'   => 'fas fa-user-graduate',
    'order'  => 3,
]);

// Teacher Management
AdminMenu::addItem(__('elearning::teacher.model_name'), [
    'id'     => ElearningAdminMenuKey::TEACHER,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.teachers.index',
    'icon'   => 'fas fa-chalkboard-teacher',
    'order'  => 4,
]);

// Students
AdminMenu::addItem(__('elearning::student.model_name'), [
    'id'     => ElearningAdminMenuKey::STUDENT,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.users.index',
    'icon'   => 'fas fa-users',
    'order'  => 5,
]);

// Membership Management
AdminMenu::addItem(__('elearning::membership.model_name'), [
    'id'     => ElearningAdminMenuKey::MEMBERSHIP,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.memberships.index',
    'icon'   => 'fas fa-id-card',
    'order'  => 6,
]);

// Payments
AdminMenu::addItem(__('elearning::payment_method.model_name'), [
    'id'     => ElearningAdminMenuKey::PAYMENT,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.payment-methods.index',
    'icon'   => 'fas fa-money-bill-wave',
    'order'  => 7,
]);

// Coupons
AdminMenu::addItem(__('elearning::coupon.model_name'), [
    'id'     => ElearningAdminMenuKey::COUPON,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.coupons.index',
    'icon'   => 'fas fa-ticket-alt',
    'order'  => 8,
]);

// Reviews Management
AdminMenu::addItem(__('elearning::review.model_name'), [
    'id'     => ElearningAdminMenuKey::REVIEW,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.reviews.index',
    'icon'   => 'fas fa-star',
    'order'  => 9,
]);

// Notes Management
AdminMenu::addItem(__('elearning::note.model_name'), [
    'id'     => ElearningAdminMenuKey::NOTE,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.notes.index',
    'icon'   => 'fas fa-star',
    'order'  => 10,
]);

// Comments Management
AdminMenu::addItem(__('elearning::comment.model_name'), [
    'id'     => ElearningAdminMenuKey::COMMENT,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.comments.index',
    'icon'   => 'fas fa-star',
    'order'  => 11,
]);

// Wishlist
AdminMenu::addItem(__('elearning::wishlist.model_name'), [
    'id'     => ElearningAdminMenuKey::WISHLIST,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.wishlists.index',
    'icon'   => 'fas fa-heart',
    'order'  => 12,
]);

// Reports & Statistics
AdminMenu::addItem(__('elearning::report.model_name'), [
    'id'     => ElearningAdminMenuKey::REPORT,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.reports.index',
    'icon'   => 'fas fa-chart-bar',
    'order'  => 13,
]);

AdminMenu::addItem(__('elearning::setting.model_name'), [
    'id'     => ElearningAdminMenuKey::SETTING,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.settings.index',
    'icon'   => 'fas fa-cog',
    'order'  => 14,
]);
