<?php
namespace MediaWiki\Extension\NewPage;

use Config;

final class Hooks
{
    /** @var Config */
    private Config $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }
}
