<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi PIK-R SKAKMATT</title>
</head>
<body style="margin: 0; padding: 0; background-color: #070b19; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e2e8f0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #070b19; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 520px; background-color: #0B1221; border-radius: 20px; border: 1px solid rgba(6, 182, 212, 0.25); overflow: hidden; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);" cellspacing="0" cellpadding="0">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 32px 24px 32px; text-align: center; background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(16, 185, 129, 0.15)); border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
                            <div style="font-size: 24px; font-weight: 800; letter-spacing: 2px; color: #22d3ee; margin-bottom: 4px;">
                                PIK-R SKAKMATT
                            </div>
                            <div style="font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">
                                Pusat Informasi & Konseling Remaja
                            </div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h2 style="font-size: 20px; font-weight: 700; color: #ffffff; margin-top: 0; margin-bottom: 12px;">
                                Halo, {{ $name }}! 👋
                            </h2>
                            <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1; margin-bottom: 24px;">
                                Terima kasih telah mendaftar akun anggota di portal <strong>PIK-R SKAKMATT</strong>. Gunakan kode verifikasi di bawah ini untuk memvalidasi pendaftaran Anda:
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #040814; border: 2px dashed #06b6d4; border-radius: 14px; padding: 22px; text-align: center; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #38bdf8; margin-bottom: 8px; font-weight: 600;">
                                    Kode Verifikasi Anda
                                </div>
                                <div style="font-size: 36px; font-weight: 900; letter-spacing: 8px; color: #34d399; font-family: 'Courier New', Courier, monospace;">
                                    {{ $code }}
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 8px;">
                                    Berlaku selama 15 menit ke depan
                                </div>
                            </div>

                            <p style="font-size: 13px; line-height: 1.5; color: #94a3b8; margin-bottom: 0;">
                                ⚠️ <em>Jangan berikan kode ini kepada siapapun. Pengurus PIK-R SKAKMATT tidak pernah meminta kode verifikasi Anda.</em>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 32px; background-color: #040814; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                            <p style="font-size: 11px; color: #64748b; margin: 0;">
                                &copy; {{ date('Y') }} PIK-R SKAKMATT. Salam Genre, Remaja Sehat, Cerdas, Ceria!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
