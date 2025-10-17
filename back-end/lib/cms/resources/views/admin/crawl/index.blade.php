@extends('core::admin.master')

@section('meta_title', __('Crawl'))

@section('page_title', __('Crawl'))

@section('page_subtitle', __('Crawl data from other websites'))

@section('breadcrumb')
  <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a>
      </li>
      <li class="breadcrumb-item active">{{ trans('Crawl') }}</li>
    </ol>
  </nav>
@stop

@section('content')
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="fs-17 font-weight-600 mb-0">
            {{ __('Crawl') }}
          </h6>
        </div>
      </div>
    </div>
    <div class="card-body">
      <h6><strong>Nhập URLs cần crawl, mỗi url cách nhau bằng dấu phẩy (,)</strong></h6>
      <form action="{{ route('cms.admin.crawl.execute') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            @textarea(['name' => 'urls', 'label' => __('URLs'), 'required' => true, 'placeholder' => 'Ví dụ: https://example.com/url-1, https://example.com/url-2'])
            @textarea(['name' => 'prompt', 'label' => __('Nhập prompt')])
            @select(['name' => 'post_action', 'label' => __('Bài viết sau khi dịch sẽ'), 'allowClear' => false, 'options' => get_post_action_options(), 'required' => true])
            <div style="display: none;" id="post_publish_date_at">
              @datetimeinput(['name' => 'schedule_at', 'label' => __('Ngày đăng bài viết'), 'required' => true])
            </div>
            @select(['name' => 'purpose_action', 'label' => __('Chọn hành động tiếp theo sau khi cào'), 'allowClear' => false, 'options' => get_action_options(), 'required' => true])
            @sumoselect(['name' => 'categories', 'label' => __('Bài viết sau rewrite sẽ lưu vào danh mục'), 'multiple' => true, 'options' => get_category_parent_options()])
            @textarea([
              'name' => 'replace_words_before',
              'label' => __('Các từ cần thay thế trước khi rewrite'),
              'placeholder' => 'Ví dụ: a|b, c|d thì từ a sẽ được thay thế b, từ c sẽ được thay thế bằng d',
              'helper' => '<i>Nguyên tắc thay thế: A|B, C|D thì từ A sẽ được thay thế bằng B, từ C sẽ được thay thế bằng D</i>',
            ])
          </div>
          <div class="col-md-6">
            @checkbox(['name' => 'is_rewrite_title', 'label' => '', 'placeholder' => __('Cho phép rewrite tiêu đề'),])
            <div style="display: none;" id="title_prompt">
              @textarea(['name' => 'title_prompt', 'label' => __('Nhập prompt cho title')])
            </div>
            @input([
              'name' => 'css_selectors',
              'label' => __('Crawl các CSS selector (lần lượt theo thứ tự)'),
              'helper' => '(<strong>Các CSS selector cần lấy theo thứ tự và phân tách bởi dấu phẩy. Ví dụ: h1.title, div.content, div#content-1, article#content-body</strong>)',
            ])
            @input([
              'name' => 'css_selectors_except',
              'label' => __('Các CSS selector loại bỏ khi cào dữ liệu (Nếu không nhập thì sẽ lấy ở phần cài đặt chung)'),
              'helper' => '<strong>Các CSS selector được phân tách bởi dấu phẩy. Ví dụ: div.breadcrumb-1, h6#title-2, article#content-5</strong>)',
            ])
            @input([
              'name' => 'length_except',
              'label' => __('Loại bỏ bài viết nếu độ dài nhỏ hơn bao nhiêu từ'),
              'default' => 500,
              'helper' => '<i>Set = 0 sẽ không loại bỏ</i>',
            ])
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" id="crawl" type="submit">Start Crawling</button>
        </div>
      </form>
    </div>
  </div>
@stop

@push('scripts')
<script>
  $(document).ready(function() {
    $('#post_action').on('change', function() {
      if ($(this).val() == 'SCHEDULE') {
        $('#post_publish_date_at').show();
      } else {
        $('#post_publish_date_at').hide();
      }
    });

    $('#is_rewrite_title').change(function() {
      if(this.checked) {
        $('#title_prompt').show();
      } else {
        $('#title_prompt').hide();
      }
    });
  });
</script>
@endpush
