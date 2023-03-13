<?php

namespace App\Controller\ApiAdmin;

use App\Document\Transfer;
use App\Manager\TransferManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TransferController extends FOSRestController
{
    /**
     * @Rest\Get("/transfers/by-wallet/{walletId}", options={"expose"=true})
     *
     * @param string $walletId
     * @param TransferManager $transferManager
     * @return View
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getTransfersByWallet(string $walletId, TransferManager $transferManager) : View
    {
        // TODO add ACL

        $transfersSerialized = [];

        $transfers = $transferManager->loadByWalletId($walletId, 100);
        if(count($transfers) > 0){
            /** @var Transfer $transfer */
            foreach($transfers as $transfer){
                $transfersSerialized[] = $transfer->serialize();
            }
        }

        return $this->view(['transfers' => $transfersSerialized], JsonResponse::HTTP_OK);
    }
}
