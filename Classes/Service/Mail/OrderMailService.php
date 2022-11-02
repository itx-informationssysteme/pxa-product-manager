<?php
declare(strict_types=1);

namespace Pixelant\PxaProductManager\Service\Mail;

use Pixelant\PxaProductManager\Exception\OrderEmailException;
use Pixelant\PxaProductManager\Utility\MainUtility;
use Pixelant\PxaProductManager\Utility\ProductUtility;

/**
 * Class OrderMailService
 *
 * @package Pixelant\PxaProductManager\Service
 */
class OrderMailService extends AbstractMailService
{
    /**
     * Prepare body to send
     *
     * @param mixed ...$variables
     *
     * @return OrderMailService
     * @throws OrderEmailException
     */
    public function generateMailBody(...$variables)
    {
        [$template, $order] = $variables;

        $standAloneView = $this->initializeStandaloneView($template);

        if (MainUtility::isPricingEnabled()) {
            $standAloneView->assign('totalPrice', ProductUtility::calculateOrderTotalPrice($order, true));
            $standAloneView->assign('totalTax', ProductUtility::calculateOrderTotalTax($order, true));
        }

        $standAloneView->assign('order', $order);

        $this->message = $standAloneView->render();

        return $this;
    }
}
