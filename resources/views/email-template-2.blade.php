<!doctype html>
<html lang="en">
  <head>
    <title>{{ $details['title'] }} - Bidme.id</title>
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
                <h3> Pemberitahuan Order Masuk Towing</h3>
                <p> Hi, {{ $details['name'] }}</p>
                <p> Anda memiliki pesanan towing masuk untuk segera diselesaikan/dijalankan, berikut rincian pesanan : </p>
                <p> Penjemputan  : {{ $details['alamatAsal'] }}</a> </p>
                <p> Tujuan  : {{ $details['alamatTujuan'] }}</a> </p>
                <p> Nomor Invoice  : {{ $details['invoice'] }}</a> </p>
                <p> Status Pembayaran  : {{ $details['payment'] }}</a> </p>
                <p> Tanggal Pembayaran  : {{ $details['paymentDate'] }}</a> </p><br>
                <p> Silahkan segera untuk pilih Driver dan masukan informasi Unit Towing anda, dengan login ke akun anda melalui website <a href="http://mitra.bidme.id/sigin"> http://mitra.bidme.id </a> </p>
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