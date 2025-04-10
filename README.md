# Ulula Tracking Module

Módulo de Magento 2 que envía notificaciones por email cuando un cliente entra al checkout.

## Características

- Envía notificaciones por email cuando un cliente entra al checkout
- Configurable desde el panel de administración de Magento
- Plantillas de email personalizables
- Soporte para múltiples destinatarios
- Logging de eventos para debugging

## Requisitos

- Magento 2.4.x
- PHP 7.4 o superior

## Instalación

1. Copia los archivos del módulo en `app/code/Ulula/Tracking`
2. Ejecuta los siguientes comandos:

```bash
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:clean
bin/magento cache:flush
```

## Configuración

1. Ve a Stores > Configuration > Ulula > Tracking
2. Habilita el módulo
3. Configura los destinatarios de email
4. Personaliza las plantillas de email si es necesario

## Uso

El módulo se activa automáticamente cuando un cliente entra al checkout. No requiere ninguna configuración adicional.

## Soporte

Para reportar bugs o solicitar características, por favor abre un issue en el repositorio.

## Licencia

Este módulo está licenciado bajo la [GNU General Public License v3.0](LICENSE).

## Créditos

Desarrollado por [Tu Nombre/Compañía] 