<?php

declare(strict_types=1);

namespace App\Domain\Notification\Exceptions;

use RuntimeException;

/**
 * Thrown if a stubbed channel (SMS/WhatsApp) is asked to deliver. The Notifier
 * skips unavailable channels, so this is a defensive guard against fabricating a
 * send when no external gateway is configured (CLAUDE §8).
 */
class ChannelUnavailableException extends RuntimeException {}
