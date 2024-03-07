<?php
namespace MediaWiki\Extension\NewPage\ResourceLoader;

use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\FileModule;
use Message;

class NewPageModule extends FileModule {
    /**
     * Get message keys used by this module.
     *
     * @return string[] List of message keys
     */
    public function getMessages() {
        $nsMessages = [];
        $nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
        foreach ( $nsInfo->getValidNamespaces() as $id ) {
            $nsMessages[] = "extnewpage-newpagetext-$id";
        }
        return array_merge( $this->messages, $nsMessages );
    }
}
