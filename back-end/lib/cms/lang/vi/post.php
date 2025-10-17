<?php
return [
    'model_name' => 'Bài viết',

    'name'        => 'Tiêu đề',
    'slug'        => 'Tiêu đề không dấu',
    'created_at'  => 'Ngày tạo',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'is_sticky'   => 'Nổi bật',
    'sort_order'  => 'Thứ tự',
    'author'      => 'Tác giả',
    'category'    => 'Danh mục',
    'image'       => 'Hình ảnh',
    'is_viewed'    => 'Lượt Xem',
    'is_created_story' => 'Tạo story?',
    'append_internal_link' => 'Thêm internal link',

    'index' => [
        'page_title'    => 'Bài viết',
        'page_subtitle' => 'Bài viết',
        'breadcrumb'    => 'Bài viết',
    ],

    'create' => [
        'page_title'    => 'Thêm bài viết',
        'page_subtitle' => 'Thêm bài viết',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa bài viết',
        'page_subtitle' => 'Sửa bài viết',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo mới bài viết thành công!',
        'updated' => 'Cập nhật bài viết thành công!',
        'deleted' => 'Xóa bài viết thành công!',
    ],

    'tabs' => [
        'info'    => 'Thông tin chung',
        'seo'     => 'SEO',
        'comment' => 'Bình luận',
        'rating'  => 'Đánh giá',
        'list'    => 'Mục lục',
        'faq' => 'FAQ',
    ],
];
