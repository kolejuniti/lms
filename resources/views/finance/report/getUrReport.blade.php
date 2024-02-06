<table id="complex_header" class="w-100 table table-bordered display margin-top-10 w-p100">
    <thead>
        <tr>
          <th>  
          </th>
          <th colspan="3">
              A Tuntutan
          </th>
          <th colspan="16">
              B Diskaun Pengajian
          </th>
          <th colspan="3">
              C Pengurangan Yuran
          </th>
          <th>
              D
          </th>
          <th >
            A-(B+C+D)
          </th>
        </tr>
        <tr>
            <th>
                Program
            </th>
            <th>
                Yuran Pengajian (RM)
            </th>
            <th>
                Nota Debit (RM) 
            </th>
            <th>
                Nota Kredit (RM)
            </th>
            <th>
                Insentif Naik Semester (RM)
            </th>
            <th>
                Insentif Pendidikan iNED (RM)
            </th>
            <th>
                UNITI Fund (RM)
            </th>
            <th>
                Biasiswa (RM)
            </th>
            <th>
                Uniti Education Fund (RM)
            </th>
            <th>
                Diskaun Covid-19/Frontliners (RM)
            </th>
            <th>
                Insentif MCO 3.0 (RM)
            </th>
            <th>
                Insentif Khas Kolej UNITI (RM)
            </th>
            <th>
                Tabung Khas B40 Kolej UNITI (RM)
            </th>
            <th>
                Tabung Khas M40 Kolej UNITI (RM)
            </th>
            <th>
                Tabung Khas T20 Kolej UNITI (RM)
            </th>
            <th>
                Tabung Khas Kolej UNITI (RM)
            </th>
            <th>
                Tabung Rahmah B40 Kolej UNITI (RM)
            </th>
            <th>
                Tabung Rahmah M40 Kolej UNITI (RM)
            </th>
            <th>
                Tabung Rahmah T20 Kolej UNITI (RM)
            </th>
            <th>
                Rabung Rahmah Kolej UNITI (RM)
            </th>
            <th>
                Nota Kredit (RM)
            </th>
            <th>
                Penerimaan Kaunter (RM)
            </th>
            <th>
                Bayaran Penaja (RM)
            </th>
            <th>
                Bayaran Lebihan (RM)
            </th>
            <th>
                Baki Tunggakan Yuran (RM)
            </th>
        </tr>
    </thead>
    <tbody id="table">
        @foreach($data['program'] as $key => $prg)
        <tr>
            <td>
            {{ $prg->progcode }}
            </td>
            <td>
            {{ $data['debt'][$key] }}
            </td>
            <td>
            {{ $data['debtND'][$key] }}
            </td>
            <td>
            {{ $data['debtNK'][$key] }}
            </td>
            <td>
            {{ $data['insentif'][$key] }}
            </td>
            <td>
            {{ $data['iNED'][$key] }}
            </td>
            <td>
            {{ $data['unitiFund'][$key] }}
            </td>
            <td>
            {{ $data['biasiswa'][$key] }}
            </td>
            <td>
            {{ $data['uef'][$key] }}
            </td>
            <td>
            {{ $data['dc19'][$key] }}
            </td>
            <td>
            {{ $data['iMCO'][$key] }}
            </td>
            <td>
            {{ $data['iKKU'][$key] }}
            </td>
            <td>
            {{ $data['tkB40'][$key] }}
            </td>
            <td>
            {{ $data['tkM40'][$key] }}
            </td>
            <td>
            {{ $data['tkT20'][$key] }}
            </td>
            <td>
            {{ $data['tk'][$key] }}
            </td>
            <td>
            {{ $data['trB40'][$key] }}
            </td>
            <td>
            {{ $data['trM40'][$key] }}
            </td>
            <td>
            {{ $data['trT20'][$key] }}
            </td>
            <td>
            {{ $data['tr'][$key] }}
            </td>
            <td>
            {{ $data['paymentNK'][$key] }}
            </td>
            <td>
            {{ $data['dailyPayment'][$key] }}
            </td>
            <td>
            {{ $data['sponsor'][$key] }}
            </td>
            <td>
            {{ $data['refund'][$key] }}
            </td>
            <td>
            {{ $data['balance'][$key] }}
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>