<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCR Perlu Ditanggapi</title>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:Arial, Helvetica, sans-serif; color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f1f5f9; margin:0; padding:24px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0"
                    style="width:640px; max-width:640px; background-color:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 8px 30px rgba(15,23,42,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg, #9cb6f3 0%, #072b77 100%); padding:32px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td valign="middle" style="width:72px;">
                                        <img src="{{ asset('img/logo-paduka-fill.svg') }}" alt="PADUKA"
                                            style="display:block; width:56px; height:56px; object-fit:contain;">
                                    </td>
                                    <td valign="middle" style="padding-left:14px;">
                                        <div style="font-size:24px; font-weight:700; color:#ffffff; line-height:1.2; letter-spacing:0.3px;">
                                            PADUKA
                                        </div>
                                        <div style="font-size:13px; color:rgba(255,255,255,0.78); line-height:1.6; padding-top:4px;">
                                            Sistem NCR Online Terpusat
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Banner / Intro --}}
                    <tr>
                        <td style="padding:0 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-top:-18px; background:#ffffff; border:1px solid #aec6dd; border-radius:14px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="font-size:12px; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:1px;">
                                            Notifikasi NCR
                                        </div>
                                        <div style="font-size:20px; font-weight:700; color:#0f172a; padding-top:6px;">
                                            Terdapat NCR yang perlu segera ditanggapi
                                        </div>
                                        <div style="font-size:14px; color:#475569; line-height:1.7; padding-top:8px;">
                                            Mohon lakukan tindak lanjut sesegera mungkin agar proses penanganan NCR dapat berjalan tepat waktu.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding:32px 36px 16px 36px;">
                            <div style="font-size:15px; color:#334155; line-height:1.8;">
                                Halo <strong>{{ $notifiable->name }}</strong>,
                            </div>

                            <div style="font-size:15px; color:#334155; line-height:1.8; padding-top:14px;">
                                Sistem mendeteksi adanya NCR yang memerlukan perhatian dan tanggapan dari Anda.
                            </div>
                        </td>
                    </tr>

                    {{-- NCR Card --}}
                    <tr>
                        <td style="padding:0 36px 8px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background:#ffffff; border:1px solid #aec6dd; border-radius:14px;">
                                <tr>
                                    <td style="padding:22px 24px;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#64748b; font-weight:700;">
                                            Nomor NCR
                                        </div>
                                        <div style="font-size:22px; line-height:1.4; font-weight:700; color:#1d4ed8; padding-top:6px; word-break:break-word;">
                                            {{ $ncr->nomor_ncr }}
                                        </div>

                                        <div style="padding-top:16px; font-size:14px; color:#475569; line-height:1.7;">
                                            Silakan buka detail NCR untuk melihat informasi lengkap dan melakukan tindak lanjut yang diperlukan.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td align="center" style="padding:28px 36px 12px 36px;">
                            <a href="{{ $url }}"
                                style="display:inline-block; background:linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; padding:14px 28px; border-radius:10px;">
                                Buka Detail NCR
                            </a>
                        </td>
                    </tr>

                    {{-- Note --}}
                    <tr>
                        <td style="padding:8px 36px 24px 36px;">
                            <div style="font-size:14px; color:#475569; line-height:1.8;">
                                Segera lakukan tindak lanjut untuk membantu memastikan proses penanganan NCR berjalan dengan baik.
                            </div>

                            <div style="font-size:14px; color:#475569; line-height:1.8; padding-top:18px;">
                                Regards,<br>
                                <strong style="color:#0f172a;">PADUKA System</strong>
                            </div>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td style="padding:0 36px;">
                            <div style="border-top:1px solid #e2e8f0;"></div>
                        </td>
                    </tr>

                    {{-- Fallback URL --}}
                    <tr>
                        <td style="padding:18px 36px 12px 36px;">
                            <div style="font-size:12px; color:#64748b; line-height:1.8;">
                                Jika tombol di atas tidak dapat diklik, silakan salin dan buka tautan berikut di browser:
                            </div>
                            <div style="padding-top:8px; font-size:12px; line-height:1.7; word-break:break-all;">
                                <a href="{{ $url }}" style="color:#2563eb; text-decoration:none;">
                                    {{ $url }}
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:22px 36px 28px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
                                <tr>
                                    <td style="padding:16px 18px;" valign="middle">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="middle" style="width:44px;">
                                                    <img src="{{ asset('img/logo-black.png') }}" alt="Logo PADUKA"
                                                        style="display:block; width:36px; height:36px; object-fit:contain;">
                                                </td>
                                                <td valign="middle" style="padding-left:10px;">
                                                    <div style="font-size:13px; font-weight:700; color:#0f172a;">
                                                        PADUKA
                                                    </div>
                                                    <div style="font-size:12px; color:#64748b; line-height:1.6;">
                                                        Email notifikasi otomatis sistem NCR
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td align="right" style="padding:16px 18px;" valign="middle">
                                        <div style="font-size:11px; color:#94a3b8; line-height:1.6;">
                                            Mohon tidak membalas email ini<br>
                                            karena dikirim secara otomatis.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>
