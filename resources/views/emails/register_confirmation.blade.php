<!-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> -->

<div role="presentation">  
  <div>
    <div>
      <div>
        <title>Kadin</title>    
        <div>          
          <table align="center" width="100%">
            <tbody>
              <tr>
                <td style="padding:10px;">
                  <table align="center" cellspacing="0" width="726">                    
                    <tbody>
                      <tr>
                        <td style="background-color:rgb(255, 255, 255);" valign="top">
                          <table style="text-align:justify;font-size:12px;line-height:8px;color:rgb(0, 0, 0);" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="590">
                            <tbody>
                              <tr>
                                <td>
                                  <a rel="nofollow" target="_blank" href="https://devtes.com/mms/" style="display:block;">
                                    <img src="https://devtes.com/mms/frontend/images/logo_kadin.png" alt="KADIN" title="Kamar Dagang Indonesia" border="0" height="76" width="700">
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>                          
                        </td>
                      </tr>
                      <tr>
                      </tr>
                      <tr>
                        <td style="padding:15px;background-color:white;" valign="top">
                          <p style="border-bottom:1px dotted rgb(239, 235, 236);width:100%;">                
                            <h1 style="font-size:18px;color:rgb(76, 72, 72);font-weight:bold;">Konfirmasi Pendaftaran</h1>                    
                          </p>
                          <p style="margin:20px 0pt 0pt;line-height:150%;">
                            Dear {{ $name }},</p>
                          <p style="margin:20px 0pt 0pt;line-height:150%;">
                            Terima kasih telah mendaftar di Kamar Dagang Indonesia.
                          </p>
                          <p align="justify" style="margin:20px 0pt 0pt;line-height:150%;">
                            Pendaftaran Anda sudah diterima dan saat ini sedang dalam proses verifikasi. Mohon kesediaannya untuk segera melunasi biaya-biaya yang diperlukan, rincian biaya yang perlu segera dilunasi tertera pada tabel di bawah. Dalam melakukan pembayaran, anda diwajibkan menyertakan code berikut: <strong>{{ $code }}</strong>. Code tersebut untuk menandai form pendaftaran anda pada sistem.
                          </p>
                          <p align="justify" style="margin:20px 0pt 0pt;line-height:150%;">
                            Untuk petunjuk selanjutnya setelah pembayaran, Mohon kesediaannya menunggu email kami dalam waktu 1x24 jam, dimana kami akan memberikan informasi akun anda. Jika dalam waktu tersebut anda belum mendapatkan e-mail berisi informasi akun anda, silahkan ....
                          </p>                          
                          <p style="margin:20px 0pt 0pt;line-height:150%;">
                            Berikut adalah rincian biaya pendaftaran anda:
                          </p>
                          <p align="right" style="font-size:10px;">
                            Registered at {{ $date }}
                          </p>        
                          <table class="table table-striped table-bordered table-hover" style="width: 100%;max-width: 100%;margin-bottom: 20px;background-color: #f9f9f9;border: 1px solid #ddd !important;">
                            <tr>
                              <td colspan="2" valign="top">
                                <div align="left">
                                  <span style="padding:5px;">Tracking Code : {{ $code }}</span>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td style="font-size:12px;background:#dceff5;font-weight:bold;">
                                Jenis Biaya
                              </td>
                              <td style="font-size:12px;background:#dceff5;font-weight:bold;">
                                Jumlah
                              </td>
                            </tr>
                            <tr>
                              <td valign="top" style="font-size:11px;border-bottom:1px solid #dee2e3;">
                                Biaya Pendaftaran Tahun Pertama
                              </td>
                              <td valign="top" style="font-size:11px;border-bottom:1px solid #dee2e3;">
                                250.000
                              </td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" style="font-size:12px;border-bottom:1px solid #dee2e3;">
                                <strong>Total Harga</strong>
                              </td>
                              <td align="right" valign="top" style="font-size:12px;border-bottom:1px solid #dee2e3;">
                                <strong>Rp 250.000</strong>
                              </td>
                            </tr>
                            <tr>                              
                              <td colspan="2" align="right" valign="top">                                
                                <a target="_blank" href="{{ url('/register1pay') }}">
                                  <input type="button" class="btn btn-primary" value="Lakukan Pembayaran" style="display: inline-block;
                                    padding: 6px 12px;
                                    margin-bottom: 0;
                                    font-size: 14px;
                                    font-weight: normal;
                                    line-height: 1.42857143;
                                    text-align: center;
                                    white-space: nowrap;
                                    vertical-align: middle;
                                    -ms-touch-action: manipulation;
                                        touch-action: manipulation;
                                    cursor: pointer;
                                    -webkit-user-select: none;
                                       -moz-user-select: none;
                                        -ms-user-select: none;
                                            user-select: none;
                                    background-image: none;
                                    border: 1px solid transparent;
                                    border-radius: 4px;
                                    color: #fff;
                                    background-color: #337ab7;
                                    border-color: #2e6da4;
                                    ">
                                </a>
                              </td>
                            </tr>
                          </table>                          
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="setting-table" style="width: 100%;max-width: 100%;margin-bottom: 20px;background-color: #f9f9f9;border: 1px solid #ddd !important;">
                            <tr>
                              <td style="background:#f7ecb5;font-weight:bold;text-align:center;">
                                Penting!!
                              </td> 
                            </tr>
                            <tr>
                              <td align="center">
                                <p>Jangan lupa untuk menyertakan tracking code anda pada keterangan transfer.</p>
                                <p>Tracking Code anda adalah: <strong>{{ $code }}</strong></p>
                                <p>&nbsp;</p>
                              </td>
                            </tr>
                          </table>                              
                          <br>                                     
                          <p align="justify">
                            Silahkan kunjungi&nbsp;<a rel="nofollow" target="_blank" href="https://devtes.com/mms/">FAQ</a>&nbsp;kami untuk tips terbaru dan pertanyaan yang sering diajukan. Silahkan jika Anda ingin menghubungi kami, jangan ragu untuk memberikan informasi <a rel="nofollow" target="_blank" href="https://devtes.com/mms/">disini</a>.
                          </p>
                          <p>
                            Hormat kami,<br>
                            <strong>Tim KADIN</strong>
                          </p>
                        </td>
                      </tr>  
                    </tbody>
                  </table>
                  <table style="font-family:Arial, Helvetica, Sans-Serif;text-align:justify;font-size:12px;line-height:18px;color:rgb(0, 0, 0);" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="700">
                    <tbody>
                      <tr>
                        <td height="" valign="middle" width="250">  
                          <p style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:rgb(91, 91, 91);margin-left:10px;margin-top:10px;">
                            <strong>Ada Pertanyaan?</strong>
                          </p>                          
                          <p style="margin-top:6px;font-family:Arial, Helvetica, sans-serif;font-size:11px;color:rgb(91, 91, 91);margin-left:10px;">
                            Kami bisa bantu Anda. Hubungi
                            Customer Service kami.                            
                            <br>                            
                            <a rel="nofollow" target="_blank" href="https://devtes.com/mms/">klik disini</a>
                          </p>
                        </td>
                        <td width="7">&nbsp;
                        </td>
                        <td align="left" valign="top" width="220">  
                          <p style="font-family:Arial, Helvetica, sans-serif;font-size:11px;color:rgb(91, 91, 91);margin-top:10px;" align="right">
                            <strong>Email ini dikirim oleh</strong>
                            <br>
                            Kamar Dagang Indonesia<br>
                            Menara Kadin Indonesia Lantai 29.<br>
                            Jl. H.R. Rasuna Said X-5 Kav. 2-3 Jakarta 12950 Indonesia<br>
                            Telepon: (62-21) 5274484 ext 126<br>
                            Fax: (62-21) 5274331, 5274332
                          </p>                                                    
                        </td>                                                
                      </tr>                    
                    </tbody>
                  </table>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" width="330">
                          <p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:rgb(91, 91, 91);margin-left:10px;">
                            <strong>Perlindungan dan Privasi Data</strong>
                          </p>
                          <p style="margin-top:10px;font-family:Arial, Helvetica, sans-serif;font-size:11px;color:rgb(91, 91, 91);margin-left:10px;">
                            Informasi Anda aman bersama kami.<br>
                            Silakan lihat <a rel="nofollow" target="_blank" href="https://devtes.com/mms/">Kebijakan Privasi</a> kami.<br>
                          </p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>                        
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>