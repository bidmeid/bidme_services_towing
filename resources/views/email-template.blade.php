<!doctype html>
<html lang="en">
  <head>
    <title>{{ $detail['title'] }} - Bidme.id</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
            <div class="card">
            <div class="card-body">
                <h3> Pemberitahuan Order Towing</h3>
                <p> Hy, {{ $detail['name'] }}</p>
                <p> Ada pesanan towing masuk dengan rincian sebagai berikut  : </p>
                <p> Penjemputan  : {{ $detail['alamatAsal'] }}</a> </p>
                <p> Tujuan  : {{ $detail['alamatTujuan'] }}</a> </p>
                <p> Untuk informasi selengkapnya silahkan login ke akun anda melalui website <a href="http://mitra.bidme.id/sigin"> http://mitra.bidme.id </a> </p>
                <p> Sebelumnya kami sangat mengapresiasi atas waktu dan partisipasi anda, serta tak lupa kami ucapkan Terimakasih.</p>
                <br/>
                <br/>
                <p>Salam Hormat</p>
                <p>Team Support </p>
            </div>
            </div>
            </div>
        </div>
    </div>
  </body>
</html>