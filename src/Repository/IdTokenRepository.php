<?php

declare(strict_types=1);

namespace League\Bundle\OAuth2ServerBundle\Repository;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Builder;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\IdTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * IdTokenRepositoryInterface
 *
 * @author Marc Riemer <mail@marcriemer.de>
 * @license http://opensource.org/licenses/MIT MIT
 */
class IdTokenRepository implements IdTokenRepositoryInterface
{
    public function __construct(
        private RequestStack $requestStack)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBuilder(AccessTokenEntityInterface $accessToken): Builder
    {
        return (new Builder(new JoseEncoder(), ChainedFormatter::withUnixTimestampDates()))
            ->identifiedBy($accessToken->getIdentifier())
            ->permittedFor($accessToken->getClient()->getIdentifier())
            ->issuedBy($this->requestStack->getCurrentRequest()->getSchemeAndHttpHost())
            ->issuedAt(new \DateTimeImmutable())
            ->canOnlyBeUsedAfter(new \DateTimeImmutable())
            ->expiresAt($accessToken->getExpiryDateTime())
            ->relatedTo($accessToken->getUserIdentifier());
    }
}
