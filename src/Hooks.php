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
			$sidebar['TOOLBOX'] = self::mergeBefore(
				$sidebar['TOOLBOX'],
				[
					'upload',
					'specialpages',
				],
				[
					'extnewpage' => [
						'href' => SpecialPage::getTitleFor( 'NewPage' )->getLocalURL(),
						'text' => $skin->msg( 'extnewpage-toolbox-label' )->text(),
						'accesskey' => Linker::accesskey( 'extnewpage' ),
					],
				]
			);
		}
	}

	private function mergeBefore( array $array, array $afterKeys, array $value ) {
		$keys = array_keys( $array );
		$pos = count( $array );
		foreach ( $afterKeys as $key ) {
			$index = array_search( $key, $keys );
			if ( $index !== false ) {
				$pos = $index;
				break;
			}
		}
		return array_merge( array_slice( $array, 0, $pos ), $value, array_slice( $array, $pos ) );
	}
}
