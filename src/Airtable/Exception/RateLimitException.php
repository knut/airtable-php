<?php

namespace Airtable\Exception;

/**
 * RateLimitException is thrown in cases where an account is putting too much
 * load on Airtable's API servers (usually by performing too many requests).
 */
class RateLimitException extends InvalidRequestException
{

}
