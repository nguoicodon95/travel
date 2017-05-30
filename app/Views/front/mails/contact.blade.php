<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ $data['subject'] }}</title>
  </head>
  <body>
      <h2 style="font-size: 17px;">{{ $data['subject'] }}</h2>
      <p>
        Có 1 yêu cầu từ người sử dụng <strong>{{ $data['name'] }}:</strong>
      </p>
      <h3>Nội dung yêu cầu:</h3>
      <p>
        {{ $data['content'] }}
      </p>

      <h3>Thông tin liên hệ của {{ $data['name'] }}</h3>
      <table style="border: 0">
          <tr>
            <td style="width: 250px;">
              Số điện thoại:
            </td>
            <td>
              {{ $data['phone'] }}
            </td>
          </tr>
          <tr>
            <td style="width: 250px;">
              Email:
            </td>
            <td>
              {{ $data['email'] }}
            </td>
          </tr>
      </table>
  </body>
</html>
