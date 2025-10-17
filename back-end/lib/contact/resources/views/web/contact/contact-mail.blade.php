
**Chào bạn,**

Có khách hàng yêu cầu liên hệ<br>

Họ và tên: **{{ trim($info->name) }}** <br>

Email: **{{ trim($info->email) }}**<br>

Số điện thoại: **{{ trim($info->phone) }}**<br>

**Nội dung yêu cầu:**<br>

{{ trim($info->content) }}<br>

Thanks,<br>
{{ config('app.name') }}<br>

---<br>
*Email này được gửi tự động, vui lòng không trả lời*
