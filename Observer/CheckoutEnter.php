<?php
namespace Ulula\Tracking\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ulula\Tracking\Model\Mail\Sender;
use Psr\Log\LoggerInterface;

class CheckoutEnter implements ObserverInterface
{
    /**
     * @var Sender
     */
    protected $sender;
    
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Sender $sender
     * @param LoggerInterface $logger
     */
    public function __construct(
        Sender $sender,
        LoggerInterface $logger
    ) {
        $this->sender = $sender;
        $this->logger = $logger;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $this->sender->sendCheckoutNotification();
        } catch (\Exception $e) {
            $this->logger->critical('Error in checkout tracking: ' . $e->getMessage());
        }
    }
} 