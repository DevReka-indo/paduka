<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCR Terlambat</title>
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
                        <td style="background:linear-gradient(135deg, #fc8d8d 0%, #7f1d1d 100%); padding:32px 36px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:72px;">
                                        <img src="{{ url('img/logo-paduka-fill.svg') }}" alt="PADUKA"
                                            style="width:56px; height:56px;">
                                    </td>
                                    <td style="padding-left:14px;">
                                        <div style="font-size:24px; font-weight:700; color:#ffffff;">
                                            PADUKA
                                        </div>
                                        <div style="font-size:13px; color:rgba(255,255,255,0.85); padding-top:4px;">
                                            Sistem NCR Online Terpusat
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Alert Banner --}}
                    <tr>
                        <td style="padding:0 36px;">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top:-18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="font-size:12px; font-weight:700; color:#dc2626; text-transform:uppercase; letter-spacing:1px;">
                                            Peringatan Keterlambatan
                                        </div>
                                        <div style="font-size:20px; font-weight:700; color:#7f1d1d; padding-top:6px;">
                                            NCR telah melewati batas waktu
                                        </div>
                                        <div style="font-size:14px; color:#7f1d1d; line-height:1.7; padding-top:8px;">
                                            Mohon segera dilakukan tindakan untuk menghindari dampak keterlambatan lebih lanjut.
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
                                Sistem mendeteksi bahwa NCR berikut telah melewati batas waktu penanganan.
                            </div>
                        </td>
                    </tr>

                    {{-- NCR Card --}}
                    <tr>
                        <td style="padding:0 36px 8px 36px;">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#fff1f2; border:1px solid #fecaca; border-radius:14px;">
                                <tr>
                                    <td style="padding:22px 24px;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#991b1b; font-weight:700;">
                                            Nomor NCR
                                        </div>
                                        <div style="font-size:22px; font-weight:700; color:#dc2626; padding-top:6px;">
                                            {{ $ncr->nomor_ncr }}
                                        </div>

                                        <div style="padding-top:16px; font-size:14px; color:#7f1d1d; line-height:1.7;">
                                            Segera buka detail NCR dan lakukan tindak lanjut untuk mengurangi risiko keterlambatan lebih lanjut.
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
                                style="display:inline-block; background:linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; padding:14px 28px; border-radius:10px;">
                                Lihat NCR Sekarang
                            </a>
                        </td>
                    </tr>

                    {{-- Note --}}
                    <tr>
                        <td style="padding:8px 36px 24px 36px;">
                            <div style="font-size:14px; color:#7f1d1d; line-height:1.8;">
                                Mohon segera ditindaklanjuti untuk memastikan proses penanganan NCR berjalan sesuai target waktu.
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
                            <div style="font-size:12px; color:#64748b;">
                                Jika tombol tidak dapat diklik:
                            </div>
                            <div style="padding-top:6px; font-size:12px; word-break:break-all;">
                                <a href="{{ $url }}" style="color:#dc2626;">
                                    {{ $url }}
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:22px 36px 28px 36px;">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
                                <tr>
                                    <td style="padding:16px;">
                                        <table>
                                            <tr>
                                                <td style="width:44px;">
                                                    <img src="{{ url('img/logo-black.png') }}" alt="Logo PADUKA"
                                                        style="width:36px;">
                                                </td>
                                                <td style="padding-left:10px;">
                                                    <div style="font-size:13px; font-weight:700; color:#0f172a;">
                                                        PADUKA
                                                    </div>
                                                    <div style="font-size:12px; color:#64748b;">
                                                        Email notifikasi otomatis sistem NCR
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td align="right" style="padding:16px;">
                                        <div style="font-size:11px; color:#94a3b8;">
                                            Mohon tidak membalas email ini<br>
                                            karena dikirim otomatis.
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
