<?php

declare(strict_types=1);

namespace League\Bundle\OAuth2ServerBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OpenidConfiguration 
{
    private $publicKeyPath;

    /**
     * Suported scopes
     *
     * @var array
     */
    private $scopes = [];

    /**
     * Supported response types
     *
     * @var array
     */
    private $responseTypes = ["code", "id_token"];

    /**
     * Supported responseModes
     *
     * @var array
     */
    private $responseModes = ["query", "fragment"];

    /**
     * Supported grant types
     *
     * @var array
     */
    private $grantTypes = [];

    /**
     * Supported endpoint auth types
     *
     * @var array
     */
    private $tokenEndpointAuthMethods = ["client_secret_basic", "client_secret_post"];

    /**
     * Suppiorted subject types
     *
     * @var array
     */
    private $subjectTypes = ["public"];

    /**
     * Suppiorted claim types
     *
     * @var array
     */
    private $claimTypes = ["normal"];

    /**
     * Supported claims
     *
     * @var array
     */
    private $claims = [];

    /**
     * Supported signing alg
     *
     * @var array
     */
    private $signingAlg = ["RS256"];

    /**
     * Get JsonResponse with openid configuration
     *
     * @param Request $request
     * @param UrlGeneratorInterface $router
     * 
     * @return JsonResponse
     */
    public function getJsonResponse(Request $request, UrlGeneratorInterface $router): JsonResponse
    {
        $json = new \stdClass();
        $json->issuer = $request->getSchemeAndHttpHost();
        $json->authorization_endpoint = $router->generate("oauth2_authorize", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $json->token_endpoint = $router->generate("oauth2_token", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $json->userinfo_endpoint = $router->generate("oauth2_userinfo", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $json->revocation_endpoint = $router->generate("oauth2_revoke", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $json->jwks_uri = $router->generate("oauth2_certs", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $json->check_session_iframe = $router->generate("oauth2_checksession", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $json->scopes_supported = $this->getScopes();
        $json->response_types_supported = $this->getResponseTypes();
        $json->grant_types_supported = $this->getGrantTypes();
        $json->response_modes_supported = $this->getResponseModes();
        $json->token_endpoint_auth_methods_supported = $this->getTokenEndpointAuthMethods();
        $json->subject_types_supported = $this->getSubjectTypes();
        $json->id_token_signing_alg_values_supported = $this->getSigningAlg();
        $json->claim_types_supported = $this->getClaimTypes();
        $json->claims_supported = $this->getClaims();

        // https://openid.net/specs/openid-connect-logout-1_0-04.html
        // $json->http_logout_supported = true;
        // $json->logout_session_supported = true;
        // $json->logout_session_required = true;

        // https://openid.net/specs/openid-connect-session-1_0.html
        // https://www.ibm.com/docs/en/was-liberty/base?topic=liberty-invoking-session-management-endpoint-openid-connect
        // $json->check_session_iframe

        // https://openid.net/specs/openid-connect-rpinitiated-1_0.html#RPLogout
        // $json->end_session_endpoint = "";

        // https://openid.net/specs/openid-connect-frontchannel-1_0.html        
        // $json->frontchannel_logout_supported;
        // $json->frontchannel_logout_session_supported;
        // $json->frontchannel_logout_uri;
        
        return new JsonResponse($json);
    }

    /**
     * Get suported scopes
     *
     * @return  array
     */ 
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Set suported scopes
     *
     * @param  array  $scopes  Suported scopes
     *
     * @return  self
     */ 
    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Get supported response types
     *
     * @return  array
     */ 
    public function getResponseTypes(): array
    {
        return $this->responseTypes;
    }

    /**
     * Set supported response types
     *
     * @param  array  $responseTypes  Supported response types
     *
     * @return  self
     */ 
    public function setResponseTypes(array $responseTypes)
    {
        $this->responseTypes = $responseTypes;

        return $this;
    }

    /**
     * Get supported grant types
     *
     * @return  array
     */ 
    public function getGrantTypes(): array
    {
        return $this->grantTypes;
    }

    /**
     * Set supported grant types
     *
     * @param  array  $grantTypes  Supported grant types
     *
     * @return  self
     */ 
    public function setGrantTypes(array $grantTypes)
    {
        $this->grantTypes = $grantTypes;

        return $this;
    }

    /**
     * Get supported endpoint auth types
     *
     * @return  array
     */ 
    public function getTokenEndpointAuthMethods(): array
    {
        return $this->tokenEndpointAuthMethods;
    }

    /**
     * Set supported endpoint auth types
     *
     * @param  array  $tokenEndpointAuthMethods  Supported endpoint auth types
     *
     * @return  self
     */ 
    public function setTokenEndpointAuthMethods(array $tokenEndpointAuthMethods)
    {
        $this->tokenEndpointAuthMethods = $tokenEndpointAuthMethods;

        return $this;
    }

    /**
     * Get suppiorted subject types
     *
     * @return  array
     */ 
    public function getSubjectTypes(): array
    {
        return $this->subjectTypes;
    }

    /**
     * Set suppiorted subject types
     *
     * @param  array  $subjectTypes  Suppiorted subject types
     *
     * @return  self
     */ 
    public function setSubjectTypes(array $subjectTypes)
    {
        $this->subjectTypes = $subjectTypes;

        return $this;
    }

    /**
     * Get suppiorted claim types
     *
     * @return  array
     */ 
    public function getClaimTypes(): array
    {
        return $this->claimTypes;
    }

    /**
     * Set suppiorted claim types
     *
     * @param  array  $claimTypes  Suppiorted claim types
     *
     * @return  self
     */ 
    public function setClaimTypes(array $claimTypes)
    {
        $this->claimTypes = $claimTypes;

        return $this;
    }

    /**
     * Get supported claims
     *
     * @return  array
     */ 
    public function getClaims(): array
    {
        return $this->claims;
    }

    /**
     * Set supported claims
     *
     * @param  array  $claims  Supported claims
     *
     * @return  self
     */ 
    public function setClaims(array $claims)
    {
        $this->claims = $claims;

        return $this;
    }

    /**
     * Get supported signing alg
     *
     * @return  array
     */ 
    public function getSigningAlg(): array
    {
        return $this->signingAlg;
    }

    /**
     * Set supported signing alg
     *
     * @param  array  $signingAlg  Supported signing alg
     *
     * @return  self
     */ 
    public function setSigningAlg(array $signingAlg)
    {
        $this->signingAlg = $signingAlg;

        return $this;
    }

    /**
     * Get supported responseModes
     *
     * @return  array
     */ 
    public function getResponseModes(): array
    {
        return $this->responseModes;
    }

    /**
     * Set supported responseModes
     *
     * @param  array  $responseModes  Supported responseModes
     *
     * @return  self
     */ 
    public function setResponseModes(array $responseModes)
    {
        $this->responseModes = $responseModes;

        return $this;
    }

    /**
     * Get the value of publicKeyPath
     */ 
    public function getPublicKeyPath()
    {
        return $this->publicKeyPath;
    }

    /**
     * Set the value of publicKeyPath
     *
     * @return  self
     */ 
    public function setPublicKeyPath($publicKeyPath)
    {
        $this->publicKeyPath = $publicKeyPath;

        return $this;
    }
}