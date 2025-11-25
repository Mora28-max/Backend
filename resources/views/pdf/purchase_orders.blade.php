<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    .container { width: 100%; margin: 0 auto; }

    .header {
      width: 100%;
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .logo {
      width: 90px;
      margin-right: 10px;
    }

    .institution-title {
      flex: 1;
      text-align: center;
      font-size: 13px;
      line-height: 1.2;
      font-weight: bold;
      color: #003366;
    }

    h1 {
      font-family: "Courier New", monospace;
      font-size: 20px;
      font-weight: bold;
      text-align: center;
      margin: 10px 0 6px 0;
      letter-spacing: 1px;
    }

    .meta {
      text-align: right;
      font-family: "Courier New", monospace;
      font-size: 12px;
      margin-bottom: 8px;
    }

    .folio-number {
      color: red;
      font-weight: bold;
    }

    .box { border: 1px solid #333; padding: 6px; margin-bottom: 8px; }
    .title-box { background:#e9eef6; font-weight:700; padding:4px; margin:-6px -6px 6px -6px; }

    table { width:100%; border-collapse: collapse; }
    td, th { border: 1px solid #666; padding: 6px; vertical-align: top; }

    .no-border td, .no-border th { border: none !important; }

    .right { text-align: right; }
    .center { text-align: center; }
    .small { font-size: 11px; }

    /* 🔒 ESTA CLASE ES LA CLAVE PARA QUE DOMPDF NO ROMPA LA SECCIÓN */
    .keep-together { page-break-inside: avoid; }

  </style>
</head>
<body>
<div class="container">

  <!-- Encabezado con tabla -->
  <table width="100%" style="margin-bottom: 10px;">
    <tr>
      <td width="100" style="vertical-align: middle;">
        <img src='https://res.cloudinary.com/dloe8hfna/image/upload/v1763960318/soapamz-logo-tagline_zcmyk8.png'
             style="width: 90px; margin-right:10px;">
      </td>

      <td style="text-align: center; vertical-align: middle;">
        <div style="font-size: 13px; line-height: 1.2; font-weight: bold; color:#003366; font-family:'Courier New', monospace;">
          SISTEMA OPERADOR DE AGUA POTABLE Y <br>
          ALCANTARILLADO DEL MUNICIPIO DE ZACAPOAXTLA
        </div>
      </td>
    </tr>
  </table>

  <h1>ORDEN DE COMPRA</h1>

  <!-- Fecha y Folio -->
  <div class="meta">
    {{ $order->location ?? 'Zacapoaxtla, Puebla' }},
    a {{ \Carbon\Carbon::parse($order->created_at)->format('d \\d\\e F \\d\\e Y') }}
    <br>
    Folio: <span class="folio-number">
      {{ $order->folio ?? sprintf('OC-%s-%04d', \Carbon\Carbon::parse($order->created_at)->format('Y-m'), $order->id) }}
    </span>
  </div>

  <!-- Datos del Comprador -->
  <div class="box" style="background: #000; color: #fff; font-family: 'Courier New', monospace; font-size: 11px; border: 1px solid #000;">
    <div class="title-box" style="background:#000; color:#fff; text-align:center; border-bottom: 1px solid #fff;">
      DATOS DEL COMPRADOR
    </div>
    <table class="no-border" style="width:100%; font-family: 'Courier New', monospace; font-size:11px;">
      <tr style="background:#dddddd; color:#000;">
        <td style="width:30%; padding:6px; border-bottom:1px solid #000;"><strong>RAZÓN SOCIAL:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #000;">SISTEMA OPERADOR DE AGUA POTABLE Y ALCANTARILLADO DEL MUNICIPIO DE ZACAPOAXTLA</td>
      </tr>
      <tr style="background:#ffffff; color:#000;">
        <td style="padding:6px; border-bottom:1px solid #000;"><strong>DIRECCIÓN:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #000;">CALLE LA CONCORDIA NÚMERO 12, C.P. 73680, ZACAPOAXTLA, PUEBLA</td>
      </tr>
      <tr style="background:#dddddd; color:#000;">
        <td style="padding:6px; border-bottom:1px solid #000;"><strong>TELÉFONO:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #000;">233-314-3148</td>
      </tr>
      <tr style="background:#ffffff; color:#000;">
        <td style="padding:6px;"><strong>CORREO ELECTRÓNICO:</strong></td>
        <td style="padding:6px;">SOAPA.ZACA@GMAIL.COM</td>
      </tr>
    </table>
  </div>

  <!-- Datos del Proveedor -->
  <div class="box" style="background: #003366; color:#fff; font-family:'Courier New', monospace; font-size:11px; border:1px solid #003366;">
    <div class="title-box" style="background:#003366; color:#fff; text-align:center; border-bottom:1px solid #66a3ff;">
      DATOS DEL PROVEEDOR
    </div>
    <table class="no-border" style="width:100%; font-family:'Courier New', monospace; font-size:11px;">
      <tr style="background:#66a3ff; color:#000;">
        <td style="width:30%; padding:6px; border-bottom:1px solid #003366;"><strong>RAZÓN SOCIAL:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #003366;">{{ $provider->name }}</td>
      </tr>
      <tr style="background:#ffffff; color:#000;">
        <td style="padding:6px; border-bottom:1px solid #003366;"><strong>DIRECCIÓN:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #003366;">{{ $provider->address }}</td>
      </tr>
      <tr style="background:#66a3ff; color:#000;">
        <td style="padding:6px; border-bottom:1px solid #003366;"><strong>TELÉFONO:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #003366;">{{ $provider->phone }}</td>
      </tr>
      <tr style="background:#ffffff; color:#000;">
        <td style="padding:6px; border-bottom:1px solid #003366;"><strong>CORREO ELECTRÓNICO:</strong></td>
        <td style="padding:6px; border-bottom:1px solid #003366;">{{ $provider->email }}</td>
      </tr>
      <tr style="background:#66a3ff; color:#000;">
        <td style="padding:6px;"><strong>NÚMERO PROVEEDOR:</strong></td>
        <td style="padding:6px;">{{ $provider->code_provider }}</td>
      </tr>
    </table>
  </div>

  <!-- DATOS DE LA COMPRA -->
  <div class="box" style="background:#333; color:#fff; font-family:'Courier New', monospace; font-size:11px; border:1px solid #333;">
    <div class="title-box" style="background:#333; color:#fff; text-align:center; border-bottom:1px solid #666;">
      DATOS DE LA COMPRA
    </div>

    <table style="width:100%; font-family:'Courier New', monospace; font-size:11px;">
      <thead>
        <tr style="background:#cccccc; color:#000;">
          <th class="center" style="border:1px solid #999;">CANTIDAD</th>
          <th class="center" style="border:1px solid #999;">UNIDAD</th>
          <th style="border:1px solid #999;">DESCRIPCIÓN</th>
        </tr>
      </thead>
      <tbody>
      @foreach($order->items as $index => $it)
        <tr style="background: {{ $index % 2 == 0 ? '#f9f9f9' : '#ffffff' }}; color:#000;">
          <td class="center" style="border:1px solid #999;">{{ $it->quantity }}</td>
          <td class="center" style="border:1px solid #999;">{{ $it->material->unit->name }}</td>
          <td style="border:1px solid #999;"><strong>{{ $it->material->description }}</strong></td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <!-- 🔒 BLOQUE FIJO (DATOS PAGO + FIRMAS) -->
  <div class="keep-together">

    <!-- Datos para el Pago -->
    <div class="box" style="background:#003366; color:#fff; font-family:'Courier New', monospace; font-size:11px; border:1px solid #003366;">
      <div class="title-box" style="background:#4a6fa5; color:#fff; text-align:center; border-bottom:1px solid #003366;">
        DATOS PARA EL PAGO
      </div>

      <table class="no-border" style="width:100%; font-family:'Courier New', monospace; font-size:11px;">
        <tr style="background:#f9f9f9; color:#000;">
          <td style="width:30%; padding:6px; border-bottom:1px solid #003366;"><strong>FORMA DE PAGO:</strong></td>
          <td style="padding:6px; border-bottom:1px solid #003366;">{{ $order->payment_method }}</td>
        </tr>

        <tr style="background:#ffffff; color:#000;">
          <td style="padding:6px; border-bottom:1px solid #003366;"><strong>TIEMPO ENTREGA:</strong></td>
          <td style="padding:6px; border-bottom:1px solid #003366;">{{ $order->delivery_time }}</td>
        </tr>

        <tr style="background:#f9f9f9; color:#000;">
          <td style="padding:6px; border-bottom:1px solid #003366;"><strong>LUGAR DE ENTREGA:</strong></td>
          <td style="padding:6px; border-bottom:1px solid #003366;">{{ $order->delivery_place }}</td>
        </tr>

        <tr style="background:#ffffff; color:#000;">
          <td style="padding:6px;"><strong>NÚMERO DE CUENTA / COTIZACIÓN:</strong></td>
          <td style="padding:6px;">{{ $order->quote_number }}</td>
        </tr>
      </table>
    </div>

    <!-- Firmas -->
    <div class="box" style="background:#f0f0f0; color:#000; font-family:'Courier New', monospace; font-size:11px; border:1px solid #666; margin-top:10px;">
      <div class="title-box" style="background:#666; color:#fff; text-align:center; border-bottom:1px solid #999;">
        FIRMAS
      </div>

      <table class="no-border" style="width:100%; margin-top:6px; font-family:'Courier New', monospace; font-size:11px;">
        <tr>
          <td style="text-align:center; padding:12px; border-bottom:1px solid #999;">
            <strong>ORDEN APROBADA POR:</strong><br><br>_________________________
          </td>

          <td style="text-align:center; padding:12px; border-bottom:1px solid #999;">
            <strong>FIRMA:</strong><br><br>_________________________
          </td>
        </tr>
      </table>
    </div>

  </div> <!-- FIN KEEP-TOGETHER -->

</div> <!-- container -->
</body>
</html>
