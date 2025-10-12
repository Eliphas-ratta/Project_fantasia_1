<?php

namespace App\Service;

use App\Repository\WorldRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class WorldContextService
{
    private $requestStack;
    private $worldRepository;

    public function __construct(RequestStack $requestStack, WorldRepository $worldRepository)
    {
        $this->requestStack = $requestStack;
        $this->worldRepository = $worldRepository;
    }

    public function getCurrentWorld()
    {
        $session = $this->requestStack->getSession();
        $id = $session->get('current_world_id');

        return $id ? $this->worldRepository->find($id) : null;
    }
}
