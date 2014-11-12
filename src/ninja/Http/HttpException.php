<?php

namespace ninja;

/**
 * Class HttpException - throw anywhere in the respond flow eg. for 401, 404, 500, etc.
 */
class HttpException extends \Symfony\Component\HttpKernel\Exception\HttpException {}
