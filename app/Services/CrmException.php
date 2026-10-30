<?php

namespace App\Services;

use RuntimeException;

/** Anything that goes wrong talking to the CRM donation webhook. */
class CrmException extends RuntimeException {}
