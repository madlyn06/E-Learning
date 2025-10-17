<x-markdown>
##### Các phần chính
1. Giao diện chính

<img src="{{ asset('vendor/manage/assets/docs/admin/images/client1.png') }}" width="100%" height="530" alt="client"/>

********************************
Ở trang chính thì có thể nhập tên của khách hàng để tìm kiếm thông tin.

2. Tạo mới, hiển thị ra ngoài giao diện

Để thêm khách hàng mới, chọn ``` Thêm ``` bên góc trên phải màn hình. Sau đó nhập thông tin và submit.

<img src="{{ asset('vendor/manage/assets/docs/admin/images/client2.png') }}" width="100%" height="530" alt="client"/>

********************************

Nếu khách hàng đó có trạng thái là `Kích hoạt` thì sẽ được hiển thị ra ngoài màn hình chính.
* Lưu ý: các trường thông tin có dấu * đỏ là các trường bắt buộc phải nhập.

********************************
3. Thông tin về các hình ảnh như sau:
<img src="{{ asset('vendor/manage/assets/docs/admin/images/client5.png') }}" width="100%" height="530" alt="client"/>

- Hình đại diện sẽ hiển thị ở đây
<img src="{{ asset('vendor/manage/assets/docs/admin/images/client3.png') }}" width="100%" height="530" alt="client"/>
- Hình ảnh logo
<img src="{{ asset('vendor/manage/assets/docs/admin/images/client6.png') }}" width="100%" height="530" alt="client"/>
- Hình ảnh hover <br/>
Khi hover chuột vào hình ảnh logo sẽ hiển thị lên hình ảnh được cài đặt ở `Hình ảnh hover` này
********************************

Tương tự, để sửa thông tin đã tạo thì chỉ cần ra ngoài màn hình giao diện chính của trang khách hàng và click vào biểu tượng sửa bên cạnh biểu tượng xoá.
Sau đó ra ngoài giao diện reload lại (F5) thì sẽ thấy sự thay đổi.

3. Cấu hình SEO

Bên cạnh tab `Thông tin chung` sẽ có tab về `SEO` ở màn hình tạo mới, ở tab này chúng ta sẽ điền các thông tin về SEO cho mỗi khách hàng tương ứng.
Nếu không nhập thông tin về SEO thì mặc định sẽ được lấy trong phần đặt [SEO](/admin/manage/seo/client/edit)

</x-markdown>
