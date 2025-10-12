<?php

namespace App\Twig;

use App\Service\WorldContextService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalVariables extends AbstractExtension implements GlobalsInterface
{
    private $worldContextService;

    public function __construct(WorldContextService $worldContextService)
    {
        $this->worldContextService = $worldContextService;
    }

    public function getGlobals(): array
    {
        return [
            'currentWorld' => $this->worldContextService->getCurrentWorld(),
        ];
    }
}
