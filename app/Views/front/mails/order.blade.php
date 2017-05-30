<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Thông tin booking tour</title>
  </head>
  <body>
    <p>Xin chào Admin <a href=""></a> !</p>
  	<p><strong>{{ $data['booking']['object']->fullname or '' }}</strong> đã đăng ký đặt chỗ {{ $data['booking']['object']->created_at or '' }}</p>

  	<a href="">Chi tiết booking #{{ $data['booking']['object']->id }}</a>

  	<h2>Thông tin liên hệ</h2>
  	<table>
  		<tr>
  			<td>Họ tên:</td>
  			<td>{{ $data['booking']['object']->fullname }}</td>
  		</tr>
  		<tr>
  			<td>Email:</td>
  			<td>{{ $data['booking']['object']->email }}</td>
  		</tr>
  		<tr>
  			<td>Số ĐT:</td>
  			<td>{{ $data['booking']['object']->phone }}</td>
  		</tr>
  		<tr>
  			<td>Địa chỉ:</td>
  			<td>{{ $data['booking']['object']->address }}</td>
  		</tr>
  	</table>
    @if($data['booking']['object']->post_id != NULL)
    <p>
      Chuyến tour {{ $data['booking']['object']->fullname or '' }} quan tâm: <strong><a href="{{ _getPostLink( $data['booking']['object']->post->slug) }}">{{ $data['booking']['object']->post->title }}</a></strong>
    </p>
    @endif
  </body>
</html>
