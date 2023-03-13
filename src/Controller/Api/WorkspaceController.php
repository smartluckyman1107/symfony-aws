<?php

namespace App\Controller\Api;

use App\Manager\ListFilter\WorkspaceListFilter;
use App\Manager\ListManager\ListManager;
use App\Manager\ListManager\Paginator;
use App\Repository\POS\WorkspaceRepository;
use App\Security\VoterRoleInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class WorkspaceController extends FOSRestController
{
    /**
     * @Rest\Get("/workspaces", options={"expose"=true})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param WorkspaceRepository $workspaceRepository
     * @param ListManager $listManager
     * @return View
     * @throws \Exception
     */
    public function getWorkspaces(Request $request, WorkspaceRepository $workspaceRepository, ListManager $listManager) : View
    {
        $this->denyAccessUnlessGranted(VoterRoleInterface::ACTION_LIST, VoterRoleInterface::MODULE_WORKSPACE);

        /** @var Paginator $paginator */
        $paginator = $listManager
            ->init(new WorkspaceListFilter($request), $workspaceRepository)
            ->load();

        return $this->view($paginator, Response::HTTP_OK);
    }
}
