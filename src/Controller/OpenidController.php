<?php

namespace League\Bundle\OAuth2ServerBundle\Controller;

use League\Bundle\OAuth2ServerBundle\Service\OpenidConfiguration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Strobotti\JWK\KeyFactory;
use Strobotti\JWK\KeySet;

final class OpenidController
{
    private bool $enbaled = false;

    public function __construct(
        private OpenidConfiguration $config
    ) {}

	public function configurationAction(Request $request, UrlGeneratorInterface $router)
	{
        if (!$this->enbaled) {
            return new Response(null, 404);
        }
        return $this->config->getJsonResponse($request, $router);
    }

	public function userinfoAction(Request $request, ResourceServer $server)
	{   
        if (!$this->enbaled) {
            return new Response(null, 404);
        }
        // TODO: userinfoAction
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

        $pub = trim(file_get_contents($config->getPublicKeyPath()));
        $keyFactory = new KeyFactory();
        $options = [
            "use" => "sig",
            "alg" => "RS256",
            "kid" => hash("sha256", $pub)
        ];
        $keySet = new KeySet();
        $keySet->addKey($keyFactory->createFromPem($pub, $options));
        return new JsonResponse($keySet);
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