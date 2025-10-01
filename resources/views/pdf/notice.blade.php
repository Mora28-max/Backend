<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Notificación SOMAPAZ</title>
    <style>
       @page{size:21.6cm 32cm;margin:0}body{font-family:Century Gothic;font-size:12pt;margin:0;padding:50px;position:relative}.watermark{position:fixed;top:2mm;left:2mm;width:calc(100% - 3mm);height:calc(100% - 3mm);object-fit:cover;opacity:1;z-index:-1}.footer-left,.header-right{position:absolute;line-height:1.4}.header-right{top:130px;right:100px;text-align:right;font-size:10pt}.footer-left{top:200px;left:100px;text-align:left;font-weight:700;font-size:9pt;text-transform:uppercase}.additional-text,.main-text{left:100px;right:100px;text-align:justify;line-height:1.5;text-indent:50px;position:absolute}.main-text{top:300px;font-size:11pt}.debt-table{position:absolute;top:435px;left:100px;width:calc(100% - 200px);border-collapse:separate;border-spacing:5px;border:2px solid #add8e6;font-size:8pt}.debt-table td,.debt-table th{border:2px solid #add8e6;padding:8px;text-align:center;background-color:transparent}.pagos-transferencia .titulo-transferencia,.pagos-transferencia th{background-color:#339fff;color:#000;text-align:center;font-weight:700;padding:5px;border:1px solid #000}.debt-table th{font-weight:700}.additional-text{font-size:11pt}.additional-text1{top:520px}.additional-text2{top:750px}.pagos-transferencia{position:absolute;top:900px;left:40px;width:20%;border-collapse:collapse;font-size:7pt}.pagos-transferencia td{border:1px solid #000;padding:5px;text-align:left}
    </style>
</head>

<body>
    <img src="images/marca-agua.jpg" alt="initial" class="watermark">


    <div class="header-right">
        <div><strong>Zacapoaxtla, Pue. A {{ $notice['notification_date'] }}.</strong></div>
        <div><strong>ASUNTO:</strong> Notificación Única por adeudo</div>
        <div>de agua potable y/o drenaje.</div>
    </div>

    <div class="footer-left">
        <div>C. {{ $customer->full_name }}</div>
        <div>DIRECCIÓN: {{ $customer->address }}</div>
        <div>Id usuario: {{ $customer->id }}</div>
        <div>ESTIMADO USUARIO</div>
    </div>

    <div class="main-text">
        El SOMAPAZ, con base a lo señalado por los artículos 1, 2 fracción II, IV y V, artículo 3,
        inciso B del Decreto de Creación del Sistema Operador de Agua Potable y Alcantarillado del
        Municipio de Zacapoaxtla; así como; artículos 1, 10 fracción III, 23 fracción VII y IX, 99 fracción I de
        la Ley del Agua para el Estado de Puebla tiene la responsabilidad de prestar servicios de Agua Potable
        y Alcantarillado. Ello implica que los usuarios como es su caso, que reciben los servicios, tienen la
        obligación de pagarlos. Adeudo que a continuación se detalla:
    </div>

    <table class="debt-table">
        <tr>
            <th>PERIODO COMPRENDIDO</th>
            <th>IMPORTE</th>
        </tr>
        <tr>
            <td><strong>{{ $notice['period'] }}</strong></td>
            <td><strong>{{ $notice['amount'] }}</strong></td>
        </tr>
    </table>

    <div class="additional-text additional-text1">
        El SOMAPAZ pone a su disposición la viabilidad de solicitar un convenio de pago a plazos derivado de su rezago,
        el cual puede solicitar al momento de recibir esta notificación en las oficinas centrales. En caso de hacer caso
        omiso a la presente notificación en un <strong>término de 5 días hábiles</strong> se le comunica que el sistema
        operador cuenta con las facultades suficientes para reducirle el servicio del agua potable, con base al artículo
        Segundo fracción IV y V del decreto de creación del Organismo Operador aprobado por el H. Congreso del Estado de
        Puebla, al artículo 99, fracción 1 de la Ley de Agua para el Estado de Puebla, y por acuerdos tomados por el
        Consejo de Administración. Así mismo, le comunico que una vez hecha la reducción de suministro a su toma de agua
        y posteriormente solicite se le reinstale, tendrá que liquidar su adeudo más recargos y todos los gastos que se
        hayan originado con motivo de la reducción del servicio y por la reinstalación.
    </div>

    <div class="additional-text additional-text2">
        Cabe hacer mención que el pago por el consumo de agua es para <strong>dar Mantenimiento tanto a la línea de
            conducción como a la red de distribución y así brindarle a usted un buen servicio</strong>.
    </div>

    <div
        style="position: absolute; top: 840px; left: 100px; right: 100px; text-align: center; font-size: 11pt; line-height: 1.5;">
        A T E N T A M E N T E<br>
        <strong>SOMAPAZ</strong>, <em>no la conduce, solo la produce.</em><br>
        <em>"Por un dulce futuro, ¡cuidemos el agua!"</em><br><br>
        <hr style="border: 1px solid #000; width: 50%; margin: 10px auto;">
        <strong>ING. JUAN BERNARDO AMADOR GONZÁLEZ<br>
            DIRECTOR GENERAL DEL SOMAPAZ</strong>
    </div>

    <table class="pagos-transferencia">
        <thead>
            <tr>
                <th class="titulo-transferencia" colspan="2">PAGOS POR TRANSFERENCIA (BBVA BANCOMER)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>NO CUENTA:</td>
                <td>0450061786</td>
            </tr>
            <tr>
                <td>CLABE INTERBANCARIA:</td>
                <td>012650004500617861</td>
            </tr>
            <tr>
                <td>ENVIAR COMPROBANTE AL CELULAR:</td>
                <td>233 108 55 81</td>
            </tr>
        </tbody>
    </table>

    <div
        style="position: absolute; top: 1040px; left: 130px; width: 180px; font-size: 8pt; line-height: 1.2; text-align: center;">
        <strong>ENTREGADO POR:</strong><br>
        {{ $notice['user_name'] }}
    </div>

    <div style="position: absolute; top: 937px; right: 20px; width: 220px; text-align: center; line-height: 1.5;">
        <strong style="font-size: 10pt;">_________________________</strong><br>
        <strong style="font-size: 12pt;">Recibió</strong><br>
        <strong style="font-size: 10pt;">C. ________________________</strong><br>
        <span style="font-size: 8pt;"><strong>FOLIO:</strong> {{ $notice['tracking_folio'] }}</span>
    </div>
</body>

</html>
