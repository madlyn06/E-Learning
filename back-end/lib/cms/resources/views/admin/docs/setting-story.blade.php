<x-markdown>

#### Tạo story tự động

---------------------------------

Khi  click vào `Lưu` thì story sẽ được tạo tự động dựa trên bài viết với các nguyên tắc sau:
- Story cha có key sẽ là `slug` của bài viết.
- Trong bài viết có bao nhiêu hình sẽ có bấy nhiêu story con.
- Story sẽ được tạo tự động nếu có cấu hình `Tự động tạo story khi bài viết được tạo hoặc cập nhật` trong phần cài đặt story khi có bài viết mới hoặc bài viết được cập nhật.
- Tiêu đề story sẽ lấy từ tên hình ảnh, nếu hình ảnh không có tên thì sẽ lấy tiêu đề bài viết.
- Sau khi tạo story xong thì story đó sẽ được tự động thêm vào bài viết ở vị trí đã chọn đã phần cài đặt `Chèn story vào bài viết`
- Nhạc nền sẽ được áp dụng cho tất cả các stories được tạo ra cho tất cả bài viết, tuy nhiên bạn cũng có thể thay đổi nhạc nền khi stories được
tạo xong bằng cách vô sửa stories trong danh sách các stories.

<img src="{{ asset('vendor/cms/assets/admin/images/sta1.png') }}" width="100%" height="530" alt="SEO"/>

----------------------------------

Tuỳ theo mức độ nhiều / ít hình ảnh trong bài viết mà tốc độ tạo story sẽ diễn ra tương ứng chậm / nhanh. Sau khi tạo stories xong có
thể xem stories đã tạo trong meu `Tất cả stories`.

Sau khi đã `Lưu` xong cài đặt thì nếu bấm vào `Lưu` lại lần nữa thì sẽ không có gì xảy ra nếu không có bài viết mới được tạo hoặc thay đổi tuỳ chỉnh
`Đã tạo story?` trong phần edit bài viết.

</x-markdown>
