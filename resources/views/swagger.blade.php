<!DOCTYPE html>
<html>
<head>
    <title>Inventory API Docs</title>
    <!-- CSS de Swagger UI -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/swagger-ui/swagger-ui.css') }}">
</head>
<body>
<div id="swagger-ui"></div>

<!-- JS de Swagger UI -->
<script src="{{ asset('vendor/swagger-ui/swagger-ui-bundle.js') }}"></script>
<script src="{{ asset('vendor/swagger-ui/swagger-ui-standalone-preset.js') }}"></script>

<script>
  const ui = SwaggerUIBundle({
    url: "{{ asset('docs/inventory-api.yaml') }}",
    dom_id: '#swagger-ui',
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    layout: "BaseLayout",
    docExpansion: "none", // para que empiece colapsado
    filter: true, // barra de búsqueda
  });
</script>
</body>
</html>
