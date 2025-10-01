<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte - {{ $report['tracking_folio'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .header-section__folio,.header-section__titles{border:none;border-collapse:collapse}:root{--viridian-5:#f1f8f5;--viridian-10:#ddeee4;--viridian-20:#bdddcc;--viridian-30:#91c4ad;--viridian-40:#62a589;--viridian-50:#3f8369;--viridian-60:#2f6c56;--viridian-70:#265646;--viridian-80:#204539;--viridian-90:#1b392f;--viridian-95:#0e201b;--trout-5:#f7f8f8;--trout-10:#edeef1;--trout-20:#d7dae0;--trout-30:#b5bac4;--trout-40:#8c94a4;--trout-50:#6e7789;--trout-60:#525969;--trout-70:#484e5c;--trout-80:#3e424e;--trout-90:#373b43;--trout-95:#24262d}*{margin:0;padding:0;box-sizing:border-box;font-family:Geist,sans-serif}.header-section__info,.main{width:100%}.header-section{width:100%;color:#fff;background-color:var(--viridian-95)}.header-section__container{padding:1rem}.header-section__title{font-size:1rem;margin-bottom:.8rem;text-transform:uppercase;font-weight:700}.header-section__folio{text-align:right}.header-section__subtitle{font-size:.7rem;margin-bottom:.5rem;text-transform:uppercase}.header-section__folio--data{font-size:1rem;text-transform:uppercase}.customer-section,.report-section{width:100%;padding:.8rem;border-bottom:.3px solid var(--viridian-20)}.customer-section__table,.report-section__table{width:100%;border-collapse:collapse;margin-bottom:.5rem}.customer-section__table:last-of-type,.report-section__table:last-of-type{margin-bottom:0}.customer-section__title,.report-section__title{font-size:1rem;text-transform:uppercase;margin-bottom:.5rem;color:var(--trout-90)}.customer-section__row,.report-section__row{width:100%;font-size:.7rem;font-weight:400}.customer-section__row--body,.customer-section__row--title,.report-section__row--body,.report-section__row--title{width:50%;text-align:left}.customer-section__row--title,.report-section__row--title{font-weight:400;color:var(--trout-60)}.customer-section__row--body,.report-section__description,.report-section__row--body{font-size:.8rem;padding:2px 0;color:var(--trout-95)}.report-section__row--priority{background-color:var(--trout-10);padding:.2rem 1rem;border-radius:.5rem;border:.5px solid var(--trout-30);color:var(--trout-60);font-weight:700;text-transform:uppercase}.report-section__description,.report-section__list{font-size:.7rem;background-color:var(--viridian-10);border-left:4px solid var(--viridian-50);padding:.5rem}.report-section__description{width:94%;line-height:1.4}.report-section__table-img{width:95%;margin:0;border-collapse:collapse}.report-section__row--body-img,.report-section__row--foot{text-align:center;color:var(--trout-70)}.report-section__list{list-style:none;margin:0;width:94%}.report-section--follow-up{background-color:var(--viridian-5)}.report-section__row--priority--small{font-size:.7rem;padding:.1rem .2rem}
    </style>
</head>

<body>
    <header class="header-section">
        <div class="header-section__container">
            <table class="header-section__info">
                <tr class="header-section__titles">
                    <td class="header-section__logo">
                        <img src='https://res.cloudinary.com/dn2wntbns/image/upload/v1755893909/soapamz-logo_aeroch.png'
                            alt="Logo" width="50">
                    </td>
                    <td>
                        <h1 class="header-section__title">Reporte de incidencia</h1>
                        <p class="header-section__subtitle">
                            Sistema Operador de Agua Potable y Alcantarillado del
                            Municipio de Zacapoaxtla
                        </p>
                    </td>
                    <td class="header-section__folio">
                        <p class="header-section__subtitle">Folio</p>
                        <h2 class="header-section__folio--data">{{ $report['tracking_folio'] }}</h2>
                    </td>
                </tr>
            </table>
        </div>
    </header>
    <main>
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
                        <td class="customer-section__row--body">{{ $report['customer_id'] ?? 'No registrado' }}</td>
                        <td class="customer-section__row--body">
                            {{ $report['attended_name'] ?? 'Sin nombre registrado' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="customer-section__table">
                <thead class="customer-section__thead">
                    <tr class="customer-section__row">
                        <th class="customer-section__row--title">Télefono</th>
                        <th class="customer-section__row--title">Dirección</th>
                    </tr>
                </thead>
                <tbody class="customer-section__tbody">
                    <tr class="customer-section__row">
                        <td class="customer-section__row--body">
                            {{ $report['phone'] ? $report['phone'] : 'Sin teléfono registrado' }}
                        </td>
                        <td class="customer-section__row--body">
                            {{ $report['address'] ?? 'Sin dirección registrada' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="report-section">
            <h2 class="report-section__title">Clasificación del Reporte</h2>
            <table class="report-section__table">
                <thead class="report-section__thead">
                    <tr class="report-section__row">
                        <th class="report-section__row--title">Categoría</th>
                        <th class="report-section__row--title">Subcategoría</th>
                    </tr>
                </thead>
                <tbody class="report-section__tbody">
                    <tr class="report-section__row">
                        <td class="report-section__row--body">{{ $report['report_category']['name'] }}</td>
                        <td class="report-section__row--body">
                            {{ $report['report_subcategory']['name'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="report-section__table">
                <thead class="report-section__thead">
                    <tr class="report-section__row">
                        <th class="report-section__row--title">Tipo Específico</th>
                        <th class="report-section__row--title">Prioridad</th>
                    </tr>
                </thead>
                <tbody class="report-section__tbody">
                    <tr class="report-section__row">
                        <td class="report-section__row--body">
                            {{ $report['report_child_subcategory']['name'] }}
                        </td>
                        <td class="report-section__row--body">
                            <span class="report-section__row--priority">
                                {{ $report['report_priority']['name'] }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="report-section">
            <h2 class="report-section__title">Descripción del Reporte</h2>
            <p class="report-section__description">
                {{ $report['description'] }}
            </p>
        </section>
        <section class="report-section">
            <h2 class="report-section__title">Evidencia Fotográfica</h2>

            <table class="report-section__table-img">
                <tbody class="report-section__tbody-img">
                    <tr class="report-section__row-img">
                        <td class="report-section__row--body-img">
                            <img src="{{ $report['images_for_pdf'][0] ? $report['images_for_pdf'][0] : '' }}"
                                alt="Evidencia Inicial" width="150">
                        </td>
                        <td class="report-section__row--body-img">
                            <img src="{{ $report['images_for_pdf'][1] ? $report['images_for_pdf'][1] : '' }}"
                                alt="Evidencia Proceso" width="150">
                        </td>
                        <td class="report-section__row--body-img">
                            <img src="{{ $report['images_for_pdf'][2] ? $report['images_for_pdf'][2] : '' }}"
                                alt="Evidencia Final" width="150">
                        </td>
                    </tr>
                </tbody>
                <tfoot class="report-section__tfoot">
                    <tr class="report-section__row">
                        <th class="report-section__row--foot">Evidencia Inicial</th>
                        <th class="report-section__row--foot">Evidencia Proceso</th>
                        <th class="report-section__row--foot">Evidencia Final</th>
                    </tr>
                </tfoot>
            </table>
        </section>
        <section class="report-section">
            <h2 class="report-section__title">Materiales utilizados</h2>
            <ul class="report-section__list">
                @foreach ($materials as $material)
                    <li>{{ $material['quantity'] }} {{ $material['unit'] }}(s) de {{ $material['material'] }}</li>
                @endforeach
            </ul>
        </section>
        <section class="report-section report-section--follow-up">
            <h2 class="report-section__title">Seguimiento</h2>
            <table class="report-section__table">
                <thead class="report-section__thead">
                    <tr class="report-section__row">
                        <th class="report-section__row--title">Atención</th>
                        <th class="report-section__row--title">Supervisión</th>
                        <th class="report-section__row--title">Emisión</th>
                    </tr>
                </thead>
                <tbody class="report-section__tbody">
                    <tr class="report-section__row">
                        <td class="report-section__row--body">
                            ____________________________________
                            <br>
                            {{ $report['user'] }}
                        </td>
                        <td class="report-section__row--body">
                            ____________________________________
                            <br>
                            {{ $report['supervision_name'] ?? 'No asignado' }}
                        </td>
                        <td class="report-section__row--body">
                            <span class="report-section__row--priority report-section__row--priority--small">
                                {{ $report['created_at_text'] }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>
