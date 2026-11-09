<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi OTP</title>
</head>
<body style="background-color: #12181b; font-family: 'Inter', Arial, sans-serif; color: #ffffff; padding: 40px 0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #1a2228; border-radius: 12px; border: 1px solid #2a363f; padding: 40px;">
        <tr>
            <td>
                <!-- Header / Logo -->
                <h2 style="color: #ffffff; font-size: 24px; margin-bottom: 20px;">Sistem Pengadaan</h2>
                
                <p style="color: #9aa0a6; font-size: 16px; line-height: 1.5; margin-bottom: 30px;">
                    Halo! Terima kasih telah mendaftar. Silakan gunakan kode verifikasi di bawah ini untuk melanjutkan akses akun Anda.
                </p>

                <!-- Kotak OTP ala Card Acorn -->
                <div style="background-color: #242f36; border: 1px solid #33414a; border-radius: 8px; text-align: center; padding: 25px; margin-bottom: 30px;">
                    <span style="font-size: 36px; font-weight: bold; letter-spacing: 6px; color: #3b82f6;">
                        {{ $token }}
                    </span>
                </div>

                <p style="color: #9aa0a6; font-size: 14px; margin-bottom: 30px;">
                    Kode ini memiliki masa berlaku <strong>30 menit</strong>. Jangan berikan kode ini kepada siapa pun.
                </p>

                <!-- Tombol Aksi -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <a href="{{ $url }}" style="background-color: #3b82f6; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        Buka Halaman Verifikasi
                    </a>
                </div>

                <hr style="border: none; border-top: 1px solid #2a363f; margin: 30px 0;">

                <p style="color: #6b7280; font-size: 12px; text-align: center;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Hak cipta dilindungi.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>