<?php
namespace Ulula\Tracking\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'ulula_tracking/general/enabled';
    const XML_PATH_EMAIL = 'ulula_tracking/general/email';
    const XML_PATH_EMAIL_SENDER = 'ulula_tracking/general/email_sender';
    const XML_PATH_EMAIL_TEMPLATE = 'ulula_tracking/general/email_template';

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Check if module is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get recipient email
     *
     * @param int|null $storeId
     * @return string
     */
    public function getRecipientEmail($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get email sender
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEmailSender($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get email template
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEmailTemplate($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
} 