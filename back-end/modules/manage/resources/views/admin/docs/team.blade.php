<x-markdown>
##### Các phần chính
1. Giao diện chính

<img src="{{ asset('vendor/manage/assets/docs/admin/images/team.png') }}" width="100%" height="530" alt="Team"/>

********************************
Ở trang chính thì có thể nhập tên của thành viên để tìm kiếm thông tin.


2. Tạo mới, hiển thị ra ngoài giao diện

Để thêm thành viên mới vào trong team, chọn ``` Thêm ``` bên góc trên phải màn hình. Sau đó nhập thông tin và submit.

<img src="{{ asset('vendor/manage/assets/docs/admin/images/team2.png') }}" width="100%" height="530" alt="Team"/>

********************************

Nếu thành viên đó có trạng thái là `Kích hoạt` thì sẽ được hiển thị ra ngoài màn hình chính.
* Lưu ý: các trường thông tin có dấu * đỏ là các trường bắt buộc phải nhập.

<img src="{{ asset('vendor/manage/assets/docs/admin/images/team1.png') }}" width="100%" height="530" alt="Team"/>

********************************

Tương tự, để sửa thông tin đã tạo thì chỉ cần ra ngoài màn hình giao diện chính của trang Team và click vào biểu tượng sửa bên cạnh biểu tượng xoá.
Sau đó ra ngoài giao diện reload lại (F5) thì sẽ thấy sự thay đổi.

3. Cấu hình SEO

Bên cạnh tab `Thông tin chung` sẽ có tab về `SEO` ở màn hình tạo mới, ở tab này chúng ta sẽ điền các thông tin về SEO cho mỗi thành viên tương ứng.
Nếu không nhập thông tin về SEO thì mặc định sẽ được lấy trong phần đặt [SEO](/admin/manage/seo/team/edit)

</x-markdown>
