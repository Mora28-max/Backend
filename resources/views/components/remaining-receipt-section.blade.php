<section class="section">
    <header class="header-section">
        <table class="header-section__info">
            <tr class="header-section__titles">
                <td class="header-section__logo">
                    <img src="images/soapamz-logo.png" alt="Logo" class="header-section__logo-image" width="50">
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
            <p class="payment-details-section__notes--text">{{ $payment['note'] ? $payment['note'] : 'Sin notas' }}
            </p>
        </div>
    </div>
    <div class="footer-section">
        <table class="footer-section__table">
            <thead class="footer-section__thead">
                <tr class="footer-section__row">
                    <th class="footer-section__cell--title">Creado por:</th>
                    <th class="footer-section__cell--title">Firma</th>
                </tr>
            </thead>
            <tbody>
                <tr class="footer-section__row">
                    <td class="footer-section__cell--body">
                        {{ $payment['created_by'] }}
                    </td>
                    <td class="footer-section__cell--body">
                        __________________________________
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
