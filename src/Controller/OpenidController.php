<?php

namespace League\Bundle\OAuth2ServerBundle\Controller;

use Jose\Component\Core\JWKSet;
use Jose\Component\KeyManagement\JWKFactory;
use League\Bundle\OAuth2ServerBundle\Security\Exception\OAuth2AuthenticationFailedException;
use League\Bundle\OAuth2ServerBundle\Service\OpenidConfiguration;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResponseTypes\UserInfoResponse;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClaimSetRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;

final class OpenidController
{
    private bool $enbaled = false;

    public function __construct(
        private HttpMessageFactoryInterface $httpMessageFactory,
        private ResourceServer $resourceServer,
        private ClaimSetRepositoryInterface $claimSetRepository,
        private OpenidConfiguration $config
    ) {}

	public function configurationAction(Request $request, UrlGeneratorInterface $router)
	{
        if (!$this->enbaled) {
            return new Response(null, 404);
        }
        return $this->config->getJsonResponse($request, $router);
    }

	public function userinfoAction(Request $request)
	{   
        if (!$this->enbaled) {
            return new Response(null, 404);
        }

        try {
            $psr7Request = $this->resourceServer->validateAuthenticatedRequest(
                $this->httpMessageFactory->createRequest($request));
        } catch (OAuthServerException $e) {
            throw OAuth2AuthenticationFailedException::create('The resource server rejected the request.', $e);
        }

        $claimSet = $this->claimSetRepository->getClaimSetEntry($psr7Request);
        if ($claimSet) {
            return new JsonResponse($claimSet);
        }

        return new JsonResponse([]);
    }

	public function revokeAction(AccessTokenRepositoryInterface $repository)
	{
        if (!$this->enbaled) {
            return new Response(null, 404);
        }

        // TODO: revokeAction
        return new JsonResponse();
    }

    public function checkSessionAction(Request $request)
	{
        if (!$this->enbaled) {
            return new Response(null, 404);
        }

        return new Response();
    }

	public function certsAction(OpenidConfiguration $config)
	{
        if (!$this->enbaled) {
            return new Response(null, 404);
        }
        return new JsonResponse(new JWKSet([JWKFactory::createFromKeyFile($config->getPublicKeyPath())]));
    }

    /**
     * Set the value of enbaled
     *
     * @return  self
     */ 
    public function setEnbaled($enbaled)
    {
        $this->enbaled = $enbaled;

        return $this;
    }
}