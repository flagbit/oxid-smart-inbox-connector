<?php

namespace EinsUndEins\TransactionMailExtenderModule\Status;

use OxidEsales\EshopCommunity\Application\Model\Order;

class Mapping implements MappingInterface
{
    private const DATE_NOT_SET = '0000-00-00 00:00:00';

    public function getValueBy(Order $order): string
    {
        // NOT_FINISHED, OK, ERROR
        $status = $order->getFieldData('oxorder__oxtransstatus');
        $sendDate = $order->getFieldData('oxorder__oxsenddate');
        $paidDate = $order->getFieldData('oxorder__oxpaid');
        $storno = $order->getFieldData('oxorder__oxstorno');
        // ORDERFOLDER_NEW, ORDERFOLDER_FINISHED, ORDERFOLDER_PROBLEMS
        $folder = $order->getFieldData('oxorder__oxfolder');

        $isPaid = $paidDate !== null && $paidDate !== self::DATE_NOT_SET;
        $isSend = $sendDate !== null && $sendDate !== self::DATE_NOT_SET;

        if ($status === 'NOT_FINISHED') {
            return 'OrderProcessing';
        }

        if ($status === 'ERROR' || $folder === 'ORDERFOLDER_PROBLEMS') {
            return 'OrderProblem';
        }

        // TODO Check articles ($order->getOrderArticles()) for individual status informations

        if ($storno === 1) {
            return 'OrderCancelled';
        }

        if ($folder === 'ORDERFOLDER_FINISHED' && $isSend && $isPaid) {
            return 'OrderDelivered';
        }

        if ($isSend) {
            return 'OrderInTransit';
        }

        return 'OrderProcessing';
    }
}
