<?php

declare(strict_types=1);

namespace League\Bundle\OAuth2ServerBundle\Repository;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Builder;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\IdTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * IdTokenRepositoryInterface
 *
 * @author Marc Riemer <mail@marcriemer.de>
 * @license http://opensource.org/licenses/MIT MIT
 */
class IdTokenRepository implements IdTokenRepositoryInterface
{
    public function __construct(
        private RequestStack $stack,
        private SessionInterface $session)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBuilder(AccessTokenEntityInterface $accessToken): Builder
    {
        $builder = (new Builder(new JoseEncoder(), ChainedFormatter::withUnixTimestampDates()))
            ->permittedFor($accessToken->getClient()->getIdentifier())
            ->issuedBy($this->stack->getCurrentRequest()->getSchemeAndHttpHost())
            ->issuedAt(new \DateTimeImmutable())
            ->expiresAt($accessToken->getExpiryDateTime())
            ->relatedTo($accessToken->getUserIdentifier());

        if ($this->session->has('nonce')) {
            $builder->withClaim('nonce', $this->session->get('nonce'));
        }

        return $builder;
    }
}
