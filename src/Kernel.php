<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Main Symfony application kernel.
 *
 * Extends BaseKernel and uses MicroKernelTrait to allow
 * simplified configuration of routes and services without separate files,
 * while keeping the flexibility of a full kernel.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
