# Ulula Tracking

Módulo de Magento 2 que envía notificaciones por correo electrónico cuando un cliente ingresa al proceso de checkout.

## Características

- Envía un correo electrónico de notificación cuando un cliente accede al checkout
- Configurable desde el panel de administración
- Soporta clientes registrados e invitados
- Incluye información detallada en el correo (cliente, tienda, fecha/hora)
- Plantilla de correo personalizable

## Requisitos

- Magento 2.4.x
- PHP 7.4 o superior

## Instalación

### Instalación mediante Composer

```bash
composer require ulula/module-tracking
```

### Instalación manual

1. Descarga o clona este repositorio en `app/code/Ulula/Tracking`
2. Ejecuta los siguientes comandos:

```bash
bin/magento module:enable Ulula_Tracking
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

## Configuración

1. Accede al panel de administración de Magento
2. Ve a Stores > Configuration > Ulula > Tracking
3. Configura los siguientes parámetros:
   - Enable Module: Activa/desactiva el módulo
   - Email Recipient: Dirección de correo que recibirá las notificaciones
   - Email Sender: Identidad del remitente del correo
   - Email Template: Plantilla de correo a utilizar

## Configuración por línea de comandos

Puedes configurar el módulo usando la línea de comandos:

```bash
# Habilitar el módulo
bin/magento config:set ulula_tracking/general/enabled 1

# Configurar el correo destinatario
bin/magento config:set ulula_tracking/general/email tuemail@ejemplo.com

# Configurar el remitente
bin/magento config:set ulula_tracking/general/email_sender general

# Configurar la plantilla
bin/magento config:set ulula_tracking/general/email_template ulula_tracking_general_email_template
```

## Personalización

### Plantilla de correo

La plantilla de correo se encuentra en:
`app/code/Ulula/Tracking/view/frontend/email/checkout_notification.html`

Puedes personalizar el contenido y diseño del correo modificando este archivo.

## Soporte

Si encuentras algún problema o tienes sugerencias, por favor abre un issue en el repositorio.

## Licencia

[GNU General Public License v3.0](LICENSE) 