<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Booking</title>
  </head>
  <body>
        <?php
            $status = '<span class="label label-primary label-sm">Chưa giải quyết</span>';
            if ($data['transaction']->status != 0 && $data['transaction']->status != 2 && $data['transaction']->status != 3) {
                $status = '<span class="label label-success label-sm">Đã được xác nhận</span>';
            } else if($data['transaction']->status != 0 && $data['transaction']->status != 1 && $data['transaction']->status != 2) {
                $status = '<span class="label label-danger label-sm">vì một số lí do nào đó đã bị hủy</span>';
            } else if($data['transaction']->status != 0 && $data['transaction']->status != 1 && $data['transaction']->status != 3) {
                $status = '<span class="label label-danger label-sm">đang được giải quyết.</span>';
            }
        ?>
      <h2 style="font-size: 17px;">Thông báo đơn đặt hàng của bạn từ website</h2>
      <p>
        Yêu cầu đặt chỗ của bạn <strong>{!! $status !!}.</strong>
      </p>
      @if(!empty($data['transaction']->data))
      <p>
        {{ $data['transaction']->data }}
      </p>
      @endif
      <p>Những thắc mắc hoặc cần tư vấn, bạn có thể liên hệ <a href="">tại đây</a>:</p>
      <table style="border: 0">
          <tr>
            <td style="width: 250px;">
              Số điện thoại:
            </td>
            <td>
              {{ $data['base_st']['phone'] }}
            </td>
          </tr>
          <tr>
            <td style="width: 250px;">
              Email:
            </td>
            <td>
              {{ $data['base_st']['email'] }}
            </td>
          </tr>
          <tr>
            <td style="width: 250px;">
              Địa chỉ:
            </td>
            <td>
              {{ $data['base_st']['address'] }}
            </td>
          </tr>
          <tr>
            <td style="width: 250px;">
              Facebook:
            </td>
            <td>
              {{ $data['base_st']['fb_link'] }}
            </td>
          </tr>
      </table>
  </body>
</html>
