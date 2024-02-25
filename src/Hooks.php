<?php
namespace MediaWiki\Extension\NewPage;

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
		$sidebar['TOOLBOX']['extnewpage'] = [
			'href' => SpecialPage::getTitleFor( 'NewPage' )->getLocalURL(),
			'text' => $skin->msg( 'newpage-toolbox-label' )->text(),
		];
	}
}
