<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Corte de Caja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <style>
      :root{--trout-5:#f7f8f8;--trout-10:#edeef1;--trout-20:#d7dae0;--trout-30:#b5bac4;--trout-40:#8c94a4;--trout-50:#6e7789;--trout-60:#525969;--trout-70:#484e5c;--trout-80:#3e424e;--trout-90:#373b43;--trout-95:#24262d}*{margin:0;padding:0;box-sizing:border-box;font-family:Geist,sans-serif}.header-section,.section{border-right:1px dashed var(--trout-20)}.header-section{width:100%;padding:1rem 0;background-color:var(--trout-95);color:#fff}.header-section__folio,.header-section__titles{border:none;border-collapse:collapse}.cash-section,.cash-section__row,.header-section__info{width:100%}.header-section__logo{background-color:var(--trout-95);width:10%}.header-section__title{font-size:1rem;margin-bottom:.5rem;text-transform:uppercase;font-weight:700}.header-section__folio{text-align:right;padding:5px 30px 0 5px}.header-section__subtitle{font-size:.7rem;margin-bottom:.5rem;text-transform:uppercase}.header-section__folio--data{font-size:.8rem;text-transform:uppercase;margin-bottom:1rem}.breakdown-section,.responsible-section{width:100%;padding:1rem}.breakdown-section__title,.cash-section__title,.responsible-section__title{font-size:1rem;text-transform:uppercase;font-weight:700;text-align:center;color:#5d9453}.breakdown-section__table,.responsible-section__table{width:95%;border-collapse:collapse;text-align:center}.breakdown-section__cell,.breakdown-section__name,.breakdown-section__signature,.responsible-section__cell,.responsible-section__name,.responsible-section__signature{padding:.5rem}.breakdown-section__name,.responsible-section__name{color:var(--trout-70);font-size:.8rem;text-transform:uppercase}.breakdown-section__role,.breakdown-section__signature,.responsible-section__role,.responsible-section__signature{color:var(--trout-40);font-size:.7rem;text-transform:uppercase;font-weight:700}.cash-section{padding:.5rem .8rem}.cash-section__table{width:95%;border-collapse:collapse;table-layout:fixed;margin-bottom:.5rem}.cash-section__body{font-size:.8rem;font-weight:400;padding:.5rem;color:var(--trout-60);text-align:center}.cash-section__paragraph{border-radius:.5rem;padding:.5rem 0;background-color:var(--trout-5)}.cash-section__span{font-size:1.5rem;font-weight:700;color:var(--trout-60)}.breakdown-section__cell{border-bottom:1px solid var(--trout-20);font-size:.8rem;color:var(--trout-60);padding:.5rem;text-align:center}.breakdown-section__cell--billete,.breakdown-section__cell--moneda{padding:.2rem .5rem;border-radius:.5rem;font-size:.6rem;text-transform:uppercase;font-weight:700}.breakdown-section__cell--billete{background-color:var(--trout-10);color:var(--trout-60)}.breakdown-section__cell--moneda{background-color:#ffe486;color:#b54c18}.breakdown-section__foot-cell{text-align:right;font-size:1.2rem;font-weight:700;padding:.5rem;color:var(--trout-60)}.breakdown-section__foot-cell--total{font-weight:400}.breakdown-section__cell--type{text-align:left;font-weight:700;text-transform:uppercase}.footer{border-top:1px dashed var(--trout-20);padding-top:1rem;text-align:center;font-size:.7rem;color:var(--trout-40)}
    </style>
</head>

<body>
    <header class="header-section">
        <div class="header-section__container">
            <table class="header-section__info">
                <tr class="header-section__titles">
                    <td class="header-section__logo">
                        <img src="images/soapamz-logo.png" alt="Logo" class="header-section__logo-image"
                            width="50">
                    </td>
                    <td>
                        <h1 class="header-section__title">Corte de Caja</h1>
                        <p class="header-section__subtitle">
                            Sistema Operador de Agua Potable y Alcantarillado del
                            Municipio de Zacapoaxtla
                        </p>
                    </td>
                    <td class="header-section__folio">
                        <p class="header-section__subtitle">Emisión</p>
                        <h2 class="header-section__folio--data">{{ $audit['created_at'] }}</h2>
                    </td>
                </tr>
            </table>
        </div>
    </header>
    <main class="main">
        <section class="responsible-section">
            <h2 class="responsible-section__title">Usuarios responsable del arqueo</h2>
            <table class="responsible-section__table">
                <thead class="responsible-section__thead">
                    <tr class="responsible-section__row">
                        <th class="responsible-section__name">{{ $audit['issued_by'] }}</th>
                        <th class="responsible-section__name">{{ $audit['receiver'] }}</th>
                        <th class="responsible-section__name">{{ $audit['witness'] }}</th>
                    </tr>
                </thead>
                <tbody class="responsible-section__tbody">
                    <tr class="responsible-section__row">
                        <td class="responsible-section__role">Cajera Entrega</td>
                        <td class="responsible-section__role">Cajera Recibe</td>
                        <td class="responsible-section__role">Practicó Arqueo</td>
                    </tr>
                    <tr class="responsible-section__row">
                        <td class="responsible-section__signature">Firma</td>
                        <td class="responsible-section__signature">Firma</td>
                        <td class="responsible-section__signature">Firma</td>
                    </tr>
                    <tr class="responsible-section__row">
                        <td class="responsible-section__cell">
                            <br>
                            <hr>
                        </td>
                        <td class="responsible-section__cell">
                            <br>
                            <hr>
                        </td>
                        <td class="responsible-section__cell">
                            <br>
                            <hr>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="cash-section">
            <h2 class="cash-section__title">Registro</h2>
            <table class="cash-section__table">
                <tbody class="cash-section__tbody">
                    <tr class="cash-section__row">
                        <td class="cash-section__body">
                            <p class="cash-section__paragraph">
                                Total Efectivo <br> <span class="cash-section__span">{{ $audit['cash'] }}</span>
                            </p>
                        </td>
                        <td class="cash-section__body">
                            <p class="cash-section__paragraph">
                                Total Digital <br> <span class="cash-section__span">{{ $audit['digital_total'] }}</span>
                            </p>
                        </td>
                        <td class="cash-section__body">
                            <p class="cash-section__paragraph">
                                Total general <br> <span class="cash-section__span">{{ $audit['total'] }}</span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="breakdown-section">
            <h2 class="breakdown-section__title">Desglose de Efectivo Por Denominación</h2>
            <table class="breakdown-section__table">
                <thead class="breakdown-section__thead">
                    <tr class="breakdown-section__row">
                        <th class="breakdown-section__name">Denominación</th>
                        <th class="breakdown-section__name">Tipo</th>
                        <th class="breakdown-section__name">Cantidad</th>
                        <th class="breakdown-section__name">Total</th>
                    </tr>
                </thead>
                <tbody class="breakdown-section__tbody">
                    @foreach ($json_details as $detail)
                        @php
                            $key = array_key_first($detail);
                            // Elimina todos los caracteres que no sean números o punto decimal
                            $number = floatval(str_replace([',', '$'], '', $key));
                            $type = $number >= 10 ? 'Billete' : 'Moneda';
                            $cantidad = $detail[$key];
                            $total = $number * $cantidad;
                        @endphp
                        <tr class="breakdown-section__row">
                            <td class="breakdown-section__cell">
                                {{ $key }}
                            </td>
                            <td class="breakdown-section__cell">
                                <span class="breakdown-section__cell--{{ strtolower($type) }}">
                                    {{ $type }}
                                </span>
                            </td>
                            <td class="breakdown-section__cell">
                                {{ $cantidad }}
                            </td>
                            <td class="breakdown-section__cell">
                                ${{ number_format($total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="breakdown-section__row">
                        <td class="breakdown-section__foot-cell" colspan="4">
                            Total: <span
                                class="breakdown-section__foot-cell--total">{{ $audit['counted_cash'] }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </section>
        <section class="breakdown-section">
            <h2 class="breakdown-section__title">Otros Métodos de Pago</h2>
            <table class="breakdown-section__table">
                <tbody class="breakdown-section__tbody">
                    @foreach ($digital_details as $detail)
                        <tr class="breakdown-section__row">
                            <th class="breakdown-section__cell breakdown-section__cell--type">{{ $detail['type'] }}
                            </th>
                            <td class="breakdown-section__cell">{{ $detail['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="breakdown-section__row">
                        <td class="breakdown-section__foot-cell" colspan="2">
                            Total: <span class="breakdown-section__foot-cell--total">{{ $audit['digital'] }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </main>
    <footer class="footer">
        <p class="footer__text">Documento generado automáticamente el día {{ date('d/m/Y H:i:s') }}.</p>
        <p class="footer__text">Este reporte debe estar firmado por todos los usuarios responsables.</p>
    </footer>
</body>

</html>
