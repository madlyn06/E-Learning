<div id="download-area" style="display: flex; justify-content:center; margin-top: -10px;margin-bottom: 15px">
  <button class="submit" type="submit" id="btn-show-info"><span><i className="fa fa-download" style="margin-right: 10"/>Download</span></button>
</div>

<form method="post" id="form-input-code" style="margin-bottom: 15px; border: 1px solid #fa7e5f;padding: 20px;border-radius: 5px; text-align: center; display: none">
  <div class="popup-ads">
    <div style="font-weight: bold; color: red"> {{ $adsItem->title}}</div>
    <p style="color: red" id="error_code"></p>
    <div style="display:flex;justify-content: center">
      <input placeholder="Nhập mã vào đây..." name="post_password" id="code_content" style="
          padding: 5px;
          border: 0.01px solid #b4b4b4;
          border-radius: 5px;
          margin-right: 10px" type="password" size="20">

      <input id="submit-code" type="button" style="
          padding: 5px 15px;
          height: 50px;
          background: yellow;
          color: red;
          font-weight: bold;
          border: 0.01px solid red;
          border-radius: 5px;" name="Submit" value="{{ setting('name_button_confirm', 'XÁC NHẬN') }}">
    </div>
  </div>
  <input type="button" style="
    color: white;
    padding: 5px 15px;
    font-weight: bold;
    height: 50px;
    animation: blink 1s infinite;
    background: #3978ff;
    border-radius: 5px;
    margin-top: 10px;
    border: 0.01px solid #3978ff;" value="{{ setting('name_button_get_code', 'LẤY MÃ XÁC NHẬN') }}" id="openModalGetCode">
</form>

<div id="showInstructGetCode" class="modal">
  <!-- Nội dung modal -->
  <div class="modal-content-ads">
    <span class="close" id="closeModalGetCode" style="float:right; cursor:pointer;">&times;</span>
    <p> Bước 1: Copy từ khóa này</p>
    <p>
      <input placeholder="Nhập mã vào đây..." id="ads_code" style="
          padding: 5px;
          border: 0.01px solid #b4b4b4;
          border-radius: 5px" value="{{ $adsItem->name }}" size="20">

      <input id="btn-copy" type="button" style="
          padding: 5px 15px;
          background: yellow;
          color: red;
          font-weight: bold;
          border: 0.01px solid red;
          border-radius: 5px;
          margin-left: 10px" name="Submit" value="Copy">
    </p>

    <p>Bước 2: Mở Google.com <a target="_blank" href="https://www.google.com">(Nhấn vào đây mở cho nhanh) </a>sau đó dán từ khóa vừa copy vào.</p>
    <p style="display: flex; justify-content: center">
      <img loading="lazy" decoding="async" class="aligncenter wp-image-6864 size-full" src="{{ $adsItem->image_search_google ? $adsItem->image_search_google->url : '' }}" alt="{{ $adsItem->name }}" width="767">
    </p>
    <p> Bước 3: Tìm và vào kết quả như hình.</p>
    <p style="display: flex; justify-content: center">
      <img loading="lazy" decoding="async" class="aligncenter size-full wp-image-14769" src="{{ $adsItem->image ? $adsItem->image->url : '' }}" alt="{{ $adsItem->name }}" width="550">
    </p>
  </div>
</div>

<div id="showContentOfAds" class="modal-show-content">
  <!-- Nội dung modal -->
  <div class="modal-content-show-content">
    <span class="close" id="closeModalShowContent" style="float:right; cursor:pointer;">&times;</span>
    <div id="content-result"></div>
  </div>
</div>
<input type="hidden" id="app-url" value="{{ config('app.url') }}"/>
<input type="hidden" id="hashed" value="{{ $adsItem->hashed }}"/>

<style>
  @keyframes blink {
    0% {
      color: black;
    }

    50% {
      color: yellow;
    }

    100% {
      color: black;
    }
  }

  .modal,
  .modal-show-content {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
  }

  .modal-content-ads {
    margin-top: 110px !important;
    background-color: #fefefe;
    margin: 2% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    overflow-y: auto;
    max-height: 80%;
    pointer-events: auto;
  }

  .modal-content-show-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 70%;
    overflow-y: auto;
    max-height: 80%;
    pointer-events: auto;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    text-align: right;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  @media only screen and (min-device-width : 414px) and (max-device-width : 736px) and (-webkit-device-pixel-ratio : 3) {
    #code_content {
      width: 14em;
    }
  }

  @media only screen and (min-device-width : 736px) {
    #code_content {
      width: 30em;
    }
  }

  @media only screen and (min-width: 600px) {
    #ads_code {
      width: 35em;
    }
  }

  @media only screen and (max-width: 450px) {
    #ads_code {
      width: 5.5em;
    }
  }

  .invalid {
    border: 1px solid red !important;
  }
</style>
