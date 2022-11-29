<?php

declare(strict_types=1);

namespace League\Bundle\OAuth2ServerBundle\Repository;
interface UserinfoRepositoryInterface {

    public function getUserinfoByIdentifyer(string $idntifyer): array;

}