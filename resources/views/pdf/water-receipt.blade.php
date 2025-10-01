<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Agua</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <style>
       .header-section__folio,.header-section__titles{border:none;border-collapse:collapse}.data-section,.data-section__consumption,.details-section{border-bottom:.3px solid var(--blue-20)}:root{--blue-5:#f3f4ff;--blue-10:#eaeafd;--blue-20:#d8dafc;--blue-30:#b8bafa;--blue-40:#9190f5;--blue-50:#6c62f0;--blue-60:#5741e6;--blue-70:#492fd2;--blue-80:#3c27b0;--blue-90:#332290;--blue-95:#191155;--trout-5:#f7f8f8;--trout-10:#edeef1;--trout-20:#d7dae0;--trout-30:#b5bac4;--trout-40:#8c94a4;--trout-50:#6e7789;--trout-60:#525969;--trout-70:#484e5c;--trout-80:#3e424e;--trout-90:#373b43;--trout-95:#24262d}*{margin:0;padding:0;box-sizing:border-box;font-family:Geist,sans-serif}.header-section__info,.main{width:100%}.header-section{width:100%;color:#fff;background-color:var(--blue-95)}.header-section__container{padding:1rem}.header-section__title{font-size:1rem;text-transform:uppercase;font-weight:700}.header-section__folio{text-align:right}.header-section__subtitle{font-size:.7rem;text-transform:uppercase;font-weight:700}.header-section__subtitle span{font-weight:400}.header-section__folio--data{font-size:1rem;text-transform:uppercase}.data-section{width:100%;padding:.7rem}.data-section__table{width:100%;border-collapse:collapse;margin-bottom:.5rem}.data-section__table:last-of-type{margin-bottom:0}.data-section__title{font-size:.8rem;text-transform:uppercase;margin-bottom:.5rem;color:var(--trout-90)}.data-section__row{width:100%;font-size:.6rem;font-weight:400}.data-section__row--body,.data-section__row--title{width:50%;text-align:left}.data-section__row--title{font-weight:400;color:var(--trout-60)}.data-section__row--body{font-size:.7rem;padding:2px 0;color:var(--trout-95)}.data-section__consumption{padding:.5rem .8rem}.data-section__consumption,.data-section__consumption--row{width:100%}.data-section__consumption--table{width:95%;border-collapse:collapse;table-layout:fixed;margin-bottom:.5rem}.data-section__consumption--body{font-size:.6rem;font-weight:400;padding:.5rem;color:var(--trout-60);text-align:center}.data-section__consumption--paragraph{border-radius:.5rem;padding:.5rem 0;background-color:var(--blue-5)}.chart-section,.details-section{padding:.5rem .8rem}.data-section__consumption--span{font-size:1rem;color:var(--blue-70);font-weight:700}.chart-container{text-align:center}.details-section{width:100%}.details-section__table{width:95%;border-collapse:collapse}.details-section__row{border-bottom:.3px solid var(--trout-20)}.details-section__row::last-of-type{border-bottom:none}.details-section__row--excess,.details-section__row--title,.details-section__row--title-header{text-align:left;font-size:.6rem;font-weight:400;padding:.5rem 0;color:var(--trout-60)}.details-section__row--excess,.details-section__row--title-header{font-weight:700}.details-section__row--body{font-size:.7rem}.details-section__row--excess-amount{font-weight:400}.total-section{background-color:var(--blue-5);padding:0 .8rem .8rem;width:100%}.total-section__row,.total-section__table{margin-left:1.5rem;width:100%}.total-section__cell--title{text-align:left;font-size:1rem;text-transform:uppercase;color:var(--trout-70)}.total-section__cell--total{font-size:1.2rem;color:var(--blue-70)}.total-section__cell--text{text-transform:uppercase;font-size:.7rem;font-weight:700;color:var(--trout-95)}.total-section__cell--quantity-text{font-size:.7rem;font-weight:400;text-align:right;color:var(--trout-95)}.data-section__title--payment{text-align:left}.data-section__row--body-payment{font-weight:700;color:#cd0808}main::before{content:"{{ $status }}";position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-30deg);font-size:5rem;color:rgba(0,0,0,.3);white-space:nowrap;pointer-events:none}
    </style>
</head>

<body>
    <header class="header-section">
        <div class="header-section__container">
            <table class="header-section__info">
                <tr class="header-section__titles">
                    <td class="header-section__logo">
                        <img src="images/soapamz-logo.png" alt="Logo" class="header-section__logo-image" width="50">
                    </td>
                    <td>
                        <h1 class="header-section__title">SOMAPAZ</h1>
                        <p class="header-section__subtitle">
                            Sistema Operador de Agua Potable y Alcantarillado del Municipio de Zacapoaxtla
                        </p>
                        <p class="header-section__subtitle">
                            La Concordia No.12 Col. Centro, Zacapoaxtla, Pue.
                        </p>
                        <p class="header-section__subtitle">
                            Correo: <span>somapaz.zaca@gmail.com</span> | Oficina: <span>233 314 3148</span> | WhatsApp:
                            <span>233 108 55 81</span>
                        </p>
                    </td>
                    <td class="header-section__folio">
                        <p class="header-section__subtitle">Fecha de expedición</p>
                        <h2 class="header-section__folio--data">{{ now()->format('d/m/Y') }}</h2>
                    </td>
                </tr>
            </table>
        </div>
    </header>
    <main>
        <section class="data-section">
            <table class="data-section__table">
                <thead class="data-section__thead">
                    <tr>
                        <th class="data-section__title">Información del cliente</th>
                        <th class="data-section__title">Información del medidor</th>
                    </tr>
                </thead>
                <tbody class="data-section__tbody">
                    <tr class="data-section__row">
                        <th class="data-section__row--title">ID Cliente</th>
                        <th class="data-section__row--title">No. Medidor</th>
                    </tr>
                    <tr class="data-section__row">
                        <td class="data-section__row--body">{{ $customer['id'] }}</td>
                        <td class="data-section__row--body">
                            {{ $customer['meter'] ?? 'No registrado' }}
                        </td>
                    </tr>
                    <tr class="data-section__row">
                        <th class="data-section__row--title">Nombre</th>
                        <th class="data-section__row--title">Fecha de instalación</th>
                    </tr>
                    <tr class="data-section__row">
                        <td class="data-section__row--body">{{ $customer['first_name'] }} {{ $customer['last_name'] }}
                        </td>
                        <td class="data-section__row--body">
                            {{ $installation_date ?? 'No registrado' }}
                        </td>
                    </tr>
                    <tr class="data-section__row">
                        <th class="data-section__row--title">Dirección</th>
                    </tr>
                    <tr class="data-section__row">
                        <td class="data-section__row--body">{{ $customer['address'] }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="data-section__consumption">
            <h2 class="data-section__title">Lecturas de Medidor</h2>
            <table class="data-section__consumption--table">
                <tbody class="data-section__consumption--tbody">
                    <tr class="data-section__consumption--row">
                        <td class="data-section__consumption--body">
                            <p class="data-section__consumption--paragraph">
                                Lectura anterior <br> <span
                                    class="data-section__consumption--span">{{ $msc['old_reading'] ?? 'N/A' }}</span>
                            </p>
                        </td>
                        <td class="data-section__consumption--body">
                            <p class="data-section__consumption--paragraph">
                                Lectura Actual <br> <span
                                    class="data-section__consumption--span">{{ $msc['new_reading'] ?? 'N/A' }}</span>
                            </p>
                        </td>
                        <td class="data-section__consumption--body">
                            <p class="data-section__consumption--paragraph">
                                Consumo m³ <br> <span
                                    class="data-section__consumption--span">{{ $msc['difference'] ?? 'N/A' }}</span>
                            </p>
                        </td>
                        <td class="data-section__consumption--body">
                            <p class="data-section__consumption--paragraph">
                                Excedente m³ <br> <span
                                    class="data-section__consumption--span">{{ $msc['excessive'] ?? 'N/A' }}</span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="chart-section">
            <h2 class="data-section__title">Consumo Últimos Meses</h2>
            <table class="details-section__table">
                <thead class="details-section__thead">
                    <tr class="details-section__row">
                        <td class="details-section__row--title-header">Mes</td>
                        <td class="details-section__row--title-header">Año</td>
                        <td class="details-section__row--title">Lectura Correspondiente</td>
                        <td class="details-section__row--title">Metros cúbicos excedentes</td>
                        <td class="details-section__row--title">Monto</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chart_data as $data)
                        <tr class="details-section__row">
                            <td class="details-section__row--body">{{ $data['month_name'] }}</td>
                            <td class="details-section__row--body">{{ $data['year'] }}</td>
                            <td class="details-section__row--title-header">{{ $data['new_reading'] ? $data['new_reading'] : 'N/A' }}</td>
                            <td class="details-section__row--title-header">{{ $data['excessive'] ? $data['excessive'] : 'Sin excedente' }}</td>
                            <td class="details-section__row--title-header">{{ $data['total_amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <section class="details-section">
            <table class="details-section__table">
                <thead class="details-section__thead">
                    <tr class="details-section__row">
                        <td class="details-section__row--title-header">Concepto</td>
                        <td class="details-section__row--title">Monto</td>
                        <td class="details-section__row--title">Excedencias</td>
                        <td class="details-section__row--title">Descuento</td>
                        <td class="details-section__row--title">Total</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="details-section__row">
                        <td class="details-section__row--title-header">Servicio de Agua</td>
                        <td class="details-section__row--body">{{ $water }}</td>
                        <td class="details-section__row--body">{{ $excess_water }}</td>
                        <td class="details-section__row--body">{{ $water_discount }}</td>
                        <td class="details-section__row--body">{{ $subtotal_water }}</td>
                    </tr>
                    <tr class="details-section__row">
                        <td class="details-section__row--title-header">Servicio de Drenaje</td>
                        <td class="details-section__row--body">{{ $drainage }}</td>
                        <td class="details-section__row--body">{{ $excess_drainage }}</td>
                        <td class="details-section__row--body">{{ $drainage_discount }}</td>
                        <td class="details-section__row--body">{{ $drainage_subtotal }}</td>
                    </tr>
                    <tr class="details-section__row">
                        <td class="details-section__row--title-header">Recargos</td>
                        <td class="details-section__row--body">{{ $surcharge }}</td>
                        <td class="details-section__row--body"> - - - - </td>
                        <td class="details-section__row--body">{{ $surcharge_discount }}</td>
                        <td class="details-section__row--body">{{ $subtotal_surcharge }}</td>
                    </tr>
                    <tr class="details-section__row">
                        <td class="details-section__row--title">IVA</td>
                        <td class="details-section__row--body">{{ $vat }}</td>
                        <td class="details-section__row--body"> - - - - </td>
                        <td class="details-section__row--body"> - - - - </td>
                        <td class="details-section__row--body">{{ $vat }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="details-section__row--excess">Costo por metro excedido:
                            <span class="details-section__row--excess-amount">{{ $service_rate_excess }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="total-section">
            <table class="total-section__table">
                <thead class="total-section__thead">
                    <tr class="total-section__row">
                        <th class="total-section__cell total-section__cell--title">Total a Pagar</th>
                        <th class="total-section__cell total-section__cell--total">{{ $total }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="total-section__row">
                        <td class="total-section__cell total-section__cell--text" colspan="2">
                            Cantidad con letra: <span
                                class="total-section__cell--quantity-text">{{ $total_text }}</span>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="data-section">
            <table class="data-section__table">
                <thead class="data-section__thead">
                    <tr>
                        <th class="data-section__title data-section__title--payment">Información del pago</th>
                        <th class="data-section__title"></th>
                    </tr>
                </thead>
                <tbody class="data-section__tbody">
                    <tr class="data-section__row">
                        <th class="data-section__row--title">
                            Mes de adeudo: <span class="data-section__row--body-payment">{{ $month }}</span>
                        </th>
                        <th class="data-section__row--title">
                            Pague antes de: <span class="data-section__row--body-payment">{{ $last_date }}</span>
                        </th>
                        <th class="data-section__row--title">
                            Fecha de suspensión: <span
                                class="data-section__row--body-payment">{{ $suspension_date }}</span>
                        </th>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>
