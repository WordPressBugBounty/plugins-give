<?php

namespace Give\Campaigns\ValueObjects;

use Give\Framework\Support\ValueObjects\Enum;

/**
 * @since 4.0.0
 *
 * @method static CampaignType CORE()
 * @method static CampaignType PEER_TO_PEER()
 * @method bool isCore()
 * @method bool isPeerToPeer()
 */
class CampaignType extends Enum
{
    const CORE = 'core';
    const PEER_TO_PEER = 'p2p';
}
