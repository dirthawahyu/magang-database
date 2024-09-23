<!DOCTYPE html>
<html>

<body style="color: black; font-family: Arial, sans-serif;">
    <p style="color: black;">Dear {{ $fullname }},</p>
    <p style="color: black;"><b>Verification code:</b></p>
    <span style="font-size: 24px; color: blue;">{{ $otp }}</span>
    <p style="color: black;">
        Seseorang mencoba untuk login. Jika orang tersebut adalah Anda, silakan gunakan kode di atas untuk mengonfirmasi
        identitas Anda.
        Kode verifikasi ini berlaku selama 5 menit.
    </p>
    <br>
    <p style="color: red;">
        <b>Mohon catat:</b> Tidak ada yang dapat menyelesaikan proses ini tanpa email ini. Jika ini bukan Anda, mohon
        melakukan Reset Password akun Anda dan email Anda untuk mengamankan akun Anda.
    </p>
</body>

</html>
