<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use MediaWiki\Html\Html;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Title\Title;

final class SpecialNewPage extends SpecialNewPageBase {
	public function __construct() {
		parent::__construct( 'NewPage', 'edit' );
	}

	protected function getJsModuleName(): string {
		return 'ext.newpage';
	}

	/**
	 * @return \OOUI\PanelLayout[]
	 */
	protected function getHelpRailModules(): array {
		$hasSearchDigest = ExtensionRegistry::getInstance()->isLoaded( 'SearchDigest' );

		return [
			new \OOUI\PanelLayout( [
				'classes' => [ 'extnewpage-rail-module' ],
				'expanded' => false,
				'padded' => false,
				'framed' => false,
				'content' => new \OOUI\HtmlSnippet( 
				   	Html::rawElement( 'h2', [], $this->msg( 'extnewpage-help-nsheading' )->parse() )
					. Html::rawElement( 'p', [], $this->msg( 'extnewpage-help-nstext' )->parse() )
				),
			] ) ,
			new \OOUI\PanelLayout( [
				'classes' => [ 'extnewpage-rail-module' ],
				'expanded' => false,
				'padded' => false,
				'framed' => false,
				'content' => new \OOUI\HtmlSnippet( implode( ' ', [
					Html::rawElement( 'h2', [], $this->msg( 'extnewpage-help-contributeheading' )->parse() ),
					$this->msg( 'extnewpage-help-contributetext' )->parse(),
					$hasSearchDigest ? $this->msg( 'extnewpage-help-contributetext-searchdigest' )->parse() : false,
				] ) ),
			] ),
		];
	}

	protected function getFormFields() {
		return [
			'title' => [
				'class' => HtmlComplexTitleField::class,
				'creatable' => true,
				'required' => true,
            ]
		];
	}

    public function onSubmit( array $data ) {
		$title = Title::makeTitleSafe( $data['title']['ns'], $data['title']['text'] );
		$url = $title->getFullUrlForRedirect( [
			'action' => 'edit',
		] );
		$this->getOutput()->redirect( $url );
    }
}
