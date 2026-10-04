<?php

namespace App\Handler;

use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, \Symfony\Component\Security\Core\Exception\AccessDeniedException $accessDeniedException): Response
    {
        return new RedirectResponse('/');
    }
}
