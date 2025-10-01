<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo - {{ $payment['payment_folio'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --charlotte-5: #eefcfd;
            --charlotte-10: #d5f5f8;
            --charlotte-20: #b3ebf2;
            --charlotte-30: #79d9e7;
            --charlotte-40: #3bc0d5;
            --charlotte-50: #1fa3bb;
            --charlotte-60: #1d839d;
            --charlotte-70: #1e6a80;
            --charlotte-80: #215769;
            --charlotte-90: #1f4a5a;
            --charlotte-95: #0f303d;
            --trout-5: #f7f8f8;
            --trout-10: #edeef1;
            --trout-20: #d7dae0;
            --trout-30: #b5bac4;
            --trout-40: #8c94a4;
            --trout-50: #6e7789;
            --trout-60: #525969;
            --trout-70: #484e5c;
            --trout-80: #3e424e;
            --trout-90: #373b43;
            --trout-95: #24262d
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Geist, sans-serif
        }

        .header-section,
        .section {
            border-right: 1px dashed var(--charlotte-20)
        }

        .footer-section__row,
        .main,
        .total-section__row {
            width: 100%
        }

        .header-section {
            width: 100%;
            color: #fff
        }

        .header-section__folio,
        .header-section__titles {
            border: none;
            border-collapse: collapse
        }

        .header-section__info {
            width: 100%;
            background-color: var(--charlotte-95)
        }

        .header-section__logo {
            background-color: var(--charlotte-95);
            width: 10%
        }

        .header-section__title {
            font-size: 1rem;
            margin-bottom: .5rem;
            text-transform: uppercase;
            font-weight: 700
        }

        .header-section__folio {
            text-align: right;
            padding: 5px 30px 0 5px
        }

        .header-section__subtitle {
            font-size: .6rem;
            text-transform: uppercase
        }

        .header-section__folio--data {
            font-size: .8rem;
            text-transform: uppercase;
            margin-bottom: 1rem
        }

        .customer-section,
        .detail-section,
        .payment-details-section,
        .total-section {
            width: 100%;
            padding: .7rem;
            border-bottom: .3px solid var(--charlotte-20)
        }

        .customer-section__table,
        .payment-details-section__table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: .5rem
        }

        .customer-section__table:last-of-type,
        .payment-details-section__table:last-of-type {
            margin-bottom: 0
        }

        .customer-section__title,
        .detail-section__title,
        .payment-details-section__title {
            font-size: 1rem;
            text-transform: uppercase;
            margin-bottom: .5rem;
            color: var(--trout-90)
        }

        .customer-section__row,
        .payment-details-section__row {
            width: 100%;
            font-size: .8rem;
            font-weight: 400
        }

        .customer-section__row--body,
        .customer-section__row--title,
        .payment-details-section__row--body,
        .payment-details-section__row--title,
        .total-section__cell {
            width: 50%;
            text-align: left
        }

        .customer-section__row--title,
        .payment-details-section__row--title {
            font-weight: 400;
            color: var(--trout-60)
        }

        .customer-section__row--body,
        .payment-details-section__row--body {
            font-size: .9rem;
            padding: 2px 0;
            color: var(--trout-95)
        }

        .details-section__table {
            width: 95%;
            border-collapse: collapse
        }

        .details-section__row {
            width: 100%;
            border-bottom: .3px solid var(--charlotte-10)
        }

        .details-section__row--title {
            padding: 5px 0;
            color: var(--trout-70);
            font-size: .7rem
        }

        .details-section__row--body {
            font-size: .8rem;
            padding: 7px 0;
            color: var(--trout-95)
        }

        .footer-section,
        .total-section {
            width: 100%;
            background-color: var(--trout-5)
        }

        .footer-section__table,
        .total-section__table {
            width: 100%;
            border-collapse: collapse
        }

        .total-section__cell--title {
            text-align: left;
            font-size: 1rem;
            text-transform: uppercase;
            color: var(--trout-70)
        }

        .total-section__cell--total {
            padding-left: 10rem;
            font-size: 1.2rem;
            color: var(--trout-90)
        }

        .total-section__cell--text {
            padding: 10px 0;
            text-transform: uppercase;
            font-size: .7rem;
            font-weight: 700;
            text-align: left;
            color: var(--trout-95)
        }

        .total-section__cell--quantity-text {
            font-size: .7rem;
            font-weight: 400;
            text-align: left;
            color: var(--trout-95)
        }

        .payment-details-section__row--body {
            text-transform: uppercase
        }

        .payment-details-section__notes-container {
            width: 92%;
            overflow: hidden;
            padding: .4rem;
            border-left: 4px solid #ffd230;
            background-color: #fef3c6
        }

        .payment-details-section__notes {
            font-size: .6rem;
            color: var(--trout-95)
        }

        .payment-details-section__notes--text {
            font-size: .7rem;
            color: var(--trout-95)
        }

        .footer-section {
            border-bottom: .3px solid var(--charlotte-20);
            padding: .7rem
        }

        .footer-section__cell--title {
            text-align: left;
            font-size: .8rem;
            font-weight: 700;
            color: var(--trout-70)
        }

        .footer-section__cell--body {
            padding: 5px 0;
            font-size: .9rem;
            color: var(--trout-95)
        }

        .description-section {
            padding: .5rem
        }

        .description-section__tagline {
            font-size: .6rem;
            color: var(--trout-70);
            font-weight: 700;
            text-align: center
        }

        .description-section__contact {
            width: 100%;
            font-size: .6rem;
            color: var(--trout-95)
        }

        .description-section__contact--row {
            text-align: center
        }

        .description-section__contact--link a {
            text-decoration: none;
            font-weight: 700;
            color: var(--charlotte-60)
        }

        .description-section__alert {
            font-weight: 700;
            text-align: center;
            font-size: .5rem;
            text-transform: uppercase;
            color: #7865ef
        }

        .section::before {
            content: "ORIGINAL USUARIO";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 3rem;
            color: rgba(0, 0, 0, .1);
            white-space: nowrap;
            pointer-events: none
        }
    </style>
</head>

<body>
    <main class="main">
        <section class="section">
            <header class="header-section">
                <table class="header-section__info">
                    <tr class="header-section__titles">
                        <td class="header-section__logo">
                            <img src="images/soapamz-logo.png" alt="Logo" class="header-section__logo-image"
                                width="50">
                        </td>
                        <td>
                            <h1 class="header-section__title">Recibo de cobro</h1>
                            <p class="header-section__subtitle">
                                Sistema Operador de Agua Potable y Alcantarillado del
                                Municipio de Zacapoaxtla
                            </p>
                        </td>
                        <td class="header-section__folio">
                            <p class="header-section__subtitle">Folio</p>
                            <h2 class="header-section__folio--data">{{ $payment['payment_folio'] }}</h2>
                            <p class="header-section__subtitle">Causa</p>
                            <h2 class="header-section__folio--data">
                                {{ $payment['tracking_folio'] }}
                            </h2>
                        </td>
                    </tr>
                </table>
            </header>
            <section class="customer-section">
                <h2 class="customer-section__title">Información del cliente</h2>
                <table class="customer-section__table">
                    <thead class="customer-section__thead">
                        <tr class="customer-section__row">
                            <th class="customer-section__row--title">ID Cliente</th>
                            <th class="customer-section__row--title">Nombre</th>
                        </tr>
                    </thead>
                    <tbody class="customer-section__tbody">
                        <tr class="customer-section__row">
                            <td class="customer-section__row--body">{{ $payment['customer_id'] }}</td>
                            <td class="customer-section__row--body">
                                {{ $payment['customer'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="customer-section__table">
                    <thead class="customer-section__thead">
                        <tr class="customer-section__row">
                            <th class="customer-section__row--title">Dirección</th>
                        </tr>
                    </thead>
                    <tbody class="customer-section__tbody">
                        <tr class="customer-section__row">
                            <td class="customer-section__row--body">{{ $payment['address'] }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="customer-section__table">
                    <thead class="customer-section__thead">
                        <tr class="customer-section__row">
                            <th class="customer-section__row--title">Tipo de Uso</th>
                            <th class="customer-section__row--title">Tipo de Clasificación</th>
                        </tr>
                    </thead>
                    <tbody class="customer-section__tbody">
                        <tr class="customer-section__row">
                            <td class="customer-section__row--body">{{ $payment['use_of_type'] }}</td>
                            <td class="customer-section__row--body">{{ $payment['classification'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
            @if ($costs)
                <section class="detail-section">
                    <h2 class="detail-section__title">Detalles de Servicio</h2>
                    <table class="details-section__table">
                        <thead class="details-section__thead">
                            <tr class="details-section__row">
                                <td class="details-section__row--title">Concepto</td>
                                <td class="details-section__row--title">Importe</td>
                                <td class="details-section__row--title">IVA</td>
                                <td class="details-section__row--title">Subtotal</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($costs as $key => $value)
                                <tr class="details-section__row">
                                    @foreach ($value as $subKey => $amount)
                                        <td class="details-section__row--body">{{ $subKey }}</td>
                                        <td class="details-section__row--body">{{ $amount['amount'] }}</td>
                                        <td class="details-section__row--body">{{ $amount['vat'] }}</td>
                                        <td class="details-section__row--body">{{ $amount['total'] }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endif
            <div class="total-section">
                <table class="total-section__table">
                    <thead class="total-section__thead">
                        <tr class="total-section__row">
                            <th class="total-section__cell total-section__cell--title">Total a Pagar</th>
                            <th class="total-section__cell total-section__cell--total">{{ $payment['total'] }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="total-section__row">
                            <td class="total-section__cell total-section__cell--text" colspan="2">
                                Cantidad con letra: <span
                                    class="total-section__cell--quantity-text">{{ $payment['total_text'] }}</span>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="payment-details-section">
                <h2 class="payment-details-section__title">Información de pago</h2>
                <table class="payment-details-section__table">
                    <thead class="payment-details-section__thead">
                        <tr class="payment-details-section__row">
                            <th class="payment-details-section__row--title">Método de Pago</th>
                            <th class="payment-details-section__row--title">Fecha de Pago</th>
                        </tr>
                    </thead>
                    <tbody class="payment-details-section__tbody">
                        <tr class="payment-details-section__row">
                            <td class="payment-details-section__row--body">{{ $payment['payment_type']['name'] }}</td>
                            <td class="payment-details-section__row--body">{{ $payment['created_at_text'] }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="payment-details-section__notes-container">
                    <p class="payment-details-section__notes">Notas: </p>
                    <p class="payment-details-section__notes--text">
                        {{ $payment['note'] ? $payment['note'] : 'Sin notas' }}
                    </p>
                </div>
            </div>
            <div class="footer-section">
                <table class="footer-section__table">
                    <thead class="footer-section__thead">
                        <tr class="footer-section__row">
                            <th class="footer-section__cell--title">Creado por:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="footer-section__row">
                            <td class="footer-section__cell--body">
                                {{ $payment['created_by'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <footer class="description-section">
                <p class="description-section__tagline">
                    SOMAPAZ, no la produce, solo la conduce "Por un dulce futuro, ¡Cuidemos
                    el Agua!"
                </p>
                <table class="description-section__contact">
                    <tr class="description-section__contact--row">
                        <td class="description-section__contact--link">
                            <a href="mailto:somapaz.zaca@gmail.com">somapaz.zaca@gmail.com</a>
                        </td>
                        <td class="description-section__contact--link">
                            <a href="tel:2333143148">Oficina: 233 314 3148</a>
                        </td>
                        <td class="description-section__contact--link">
                            <a href="tel:2331085581">WhatsApp: 233 108 55 81</a>
                        </td>
                    </tr>
                </table>
                <p class="description-section__alert">
                    El pago de este recibo no libera al usuario de adeudos por rezagos anteriores.
                </p>
            </footer>
        </section>

    </main>
</body>

</html>
