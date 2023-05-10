<?php

namespace App\Tests\Controller;

use App\Controller\SecurityController;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityControllerTest extends TestCase
{
    private $requestStack;
    private $authenticationUtils;
    public function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->authenticationUtils = new AuthenticationUtils($this->requestStack);
    }

    public function testLogin(): void
    {

    }

    public function testLogout(): void
    {
        $controller = new SecurityController();
        $controller->logout();
        $this->expectException(\LogicException::class);
    }
}