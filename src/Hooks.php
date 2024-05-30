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
						'id' => 't-newpage',
						'href' => SpecialPage::getTitleFor( 'NewPage' )->getLocalURL(),
						'text' => $skin->msg( 'extnewpage-toolbox-label' )->text(),
						'accesskey' => Linker::accesskey( 'extnewpage' ),
					],
				],
			);
		}

		if ( !$skin->msg( 'extnewpage-redirect-toolbox-label' )->inContentLanguage()->isDisabled() ) {
			$query = [];
			// If this is not a virtual page, we can probably prefill the form fields.
			//
			// - If the request is for a missing page, prefill the title as the redirect title.
			// - If the request is for a page already created, prefill the title as the redirect target.
			if ( $skin->getRelevantTitle()->canExist() ) {
				$query[$skin->getRelevantTitle()->exists() ? 'nrto' : 'nrfrom'] =
					$skin->getRelevantTitle()->getFullText();
			}

			$sidebar['TOOLBOX'] = self::mergeBefore(
				$sidebar['TOOLBOX'],
				[
					'extnewpage',
					'upload',
					'specialpages',
				],
				[
					'extnewredirect' => [
						'id' => 't-newredirect',
						'href' => SpecialPage::getTitleFor( 'NewRedirect' )->getLocalURL( $query ),
						'text' => $skin->msg( 'extnewpage-redirect-toolbox-label' )->text(),
					],
				],
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
