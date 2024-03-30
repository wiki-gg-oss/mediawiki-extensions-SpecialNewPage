<?php
namespace MediaWiki\Extension\NewPage;

use Linker;
use Skin;
use SpecialPage;

final class Hooks implements
    \MediaWiki\Hook\SidebarBeforeOutputHook
{
	/**
	 * @param Skin $skin
	 * @param array &$sidebar Sidebar content
	 * @return void
	 */
	public function onSidebarBeforeOutput( $skin, &$sidebar ): void {
		if ( !$skin->msg( 'extnewpage-toolbox-label' )->inContentLanguage()->isDisabled() ) {
			$sidebar['TOOLBOX']['extnewpage'] = [
				'href' => SpecialPage::getTitleFor( 'NewPage' )->getLocalURL(),
				'text' => $skin->msg( 'extnewpage-toolbox-label' )->text(),
				'accesskey' => Linker::accesskey( 'extnewpage' ),
			];
		}
	}
}
