<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\CookieValuesAuthenticatorException;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class ForcedAuthenticationRenewalNotAllowedException extends RuntimeException implements AuthenticationException
{
    /**
     * @return ForcedAuthenticationRenewalNotAllowedException
     */
    public static function create(): ForcedAuthenticationRenewalNotAllowedException
    {
        return new self('The CookieValueAuthenticator does not support re-authentication.');
    }
}
