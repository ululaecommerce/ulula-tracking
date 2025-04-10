<?php
/**
 * Clase Sender - Encargada de gestionar el envío de correos electrónicos de notificación
 * cuando un cliente ingresa al checkout.
 */
namespace Ulula\Tracking\Model\Mail;

use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Ulula\Tracking\Helper\Data;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Sender
{
    /**
     * @var TransportBuilder
     * Constructor de transporte para correos electrónicos
     */
    protected $transportBuilder;

    /**
     * @var StateInterface
     * Gestiona el estado de la traducción en línea
     */
    protected $inlineTranslation;

    /**
     * @var StoreManagerInterface
     * Administra la información de la tienda
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     * Sistema de registro para eventos y errores
     */
    protected $logger;

    /**
     * @var Data
     * Helper que contiene la configuración del módulo
     */
    protected $helper;

    /**
     * @var CustomerSession
     * Sesión del cliente actual
     */
    protected $customerSession;

    /**
     * @var DateTime
     * Maneja fechas y horas
     */
    protected $dateTime;

    /**
     * Constructor de la clase Sender
     * 
     * Inicializa todas las dependencias necesarias para el envío de correos
     * 
     * @param TransportBuilder $transportBuilder Construye el objeto de transporte para el correo
     * @param StateInterface $inlineTranslation Maneja las traducciones inline 
     * @param StoreManagerInterface $storeManager Proporciona información sobre la tienda actual
     * @param LoggerInterface $logger Registra eventos y errores
     * @param Data $helper Proporciona acceso a la configuración del módulo
     * @param CustomerSession $customerSession Información de la sesión del cliente
     * @param DateTime $dateTime Utilidades para manejar fechas y horas
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        Data $helper,
        CustomerSession $customerSession,
        DateTime $dateTime
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->customerSession = $customerSession;
        $this->dateTime = $dateTime;
    }

    /**
     * Envía la notificación por correo electrónico cuando un cliente ingresa al checkout
     * 
     * El proceso incluye:
     * 1. Verificar si el módulo está habilitado
     * 2. Obtener el correo del destinatario desde la configuración
     * 3. Recopilar información del cliente (si está logueado o es invitado)
     * 4. Obtener información de la tienda
     * 5. Preparar las variables para la plantilla
     * 6. Configurar y enviar el correo electrónico
     *
     * @return bool Retorna true si el correo se envió correctamente, false en caso contrario
     */
    public function sendCheckoutNotification()
    {
        try {
            // Obtiene el ID de la tienda actual
            $storeId = $this->storeManager->getStore()->getId();
            
            // Verifica si el módulo está habilitado en la configuración
            if (!$this->helper->isEnabled($storeId)) {
                return false;
            }
            
            // Obtiene el correo electrónico destinatario desde la configuración
            $recipientEmail = $this->helper->getRecipientEmail($storeId);
            // Si no hay destinatario configurado, no se envía el correo
            if (!$recipientEmail) {
                return false;
            }
            
            // Obtiene información del cliente
            $customerName = 'Guest'; // Por defecto es invitado
            $customerEmail = '';
            // Si el cliente está logueado, obtiene su información
            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
                $customerName = $customer->getName();
                $customerEmail = $customer->getEmail();
            }
            
            // Obtiene información de la tienda
            $store = $this->storeManager->getStore();
            $storeName = $store->getName();
            $storeUrl = $store->getBaseUrl();
            
            // Obtiene la fecha y hora actual
            $dateTime = $this->dateTime->date('Y-m-d H:i:s');
            
            // Prepara las variables que se usarán en la plantilla de correo
            $templateVars = [
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'store_name' => $storeName,
                'store_url' => $storeUrl,
                'date_time' => $dateTime
            ];
            
            // Suspende la traducción en línea durante la generación del correo
            $this->inlineTranslation->suspend();
            
            // Construye el transporte para enviar el correo
            $transport = $this->transportBuilder
                // Establece la plantilla a utilizar desde la configuración
                ->setTemplateIdentifier($this->helper->getEmailTemplate($storeId))
                // Configura las opciones de la plantilla (área y tienda)
                ->setTemplateOptions([
                    'area' => Area::AREA_FRONTEND,
                    'store' => $storeId
                ])
                // Establece las variables que se utilizarán en la plantilla
                ->setTemplateVars($templateVars)
                // Configura el remitente del correo desde la configuración
                ->setFrom($this->helper->getEmailSender($storeId))
                // Agrega el destinatario del correo
                ->addTo($recipientEmail)
                // Obtiene el objeto de transporte listo para enviar
                ->getTransport();
            
            // Envía el mensaje
            $transport->sendMessage();
            
            // Reanuda la traducción en línea después de enviar el correo
            $this->inlineTranslation->resume();
            
            // Retorna true indicando que el correo se envió correctamente
            return true;
        } catch (\Exception $e) {
            // Registra cualquier error que ocurra durante el proceso
            $this->logger->critical('Error al enviar el correo de notificación de checkout: ' . $e->getMessage());
            // Retorna false indicando que hubo un error al enviar el correo
            return false;
        }
    }
} 