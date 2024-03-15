<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use ExtensionRegistry;
use FormSpecialPage;
use Html;
use HTMLForm;
use Title;

final class SpecialNewPage extends FormSpecialPage {
	public function __construct() {
		parent::__construct( 'NewPage', 'edit' );
	}

	/**
	 * Add header elements like block log entries, etc.
	 * @return string
	 */
	protected function preHtml() {
		$this->getOutput()->addModules( [ 'ext.newpage' ] );
		$this->getOutput()->addModuleStyles( [ 'ext.newpage.styles' ] );

		return parent::preHtml()
			// This container will be closed in postHtml
			. Html::openElement( 'div', [ 'class' => [ 'extnewpage-form-wrapper' ] ] );
	}

	/**
	 * Add post-HTML to the form
	 * @return string
	 */
	protected function postHtml() {
		return implode( '', [
			Html::openElement( 'div', [ 'class' => [ 'extnewpage-rail' ] ] ),
			implode( '', array_map( fn ( $el ) => $el->toString(), $this->getHelpRailModules() ) ),
			Html::closeElement( 'div' ),
			// Close the container opened in preHtml
			Html::closeElement( 'div' ),
		] );
	}

	/**
	 * @return \OOUI\PanelLayout[]
	 */
	private function getHelpRailModules(): array {
		$hasSearchDigest = ExtensionRegistry::getInstance()->isLoaded( 'SearchDigest' );

		return [
			new \OOUI\PanelLayout( [
				'classes' => [ 'extnewpage-rail-module' ],
				'expanded' => false,
				'padded' => true,
				'framed' => false,
				'content' => new \OOUI\HtmlSnippet( 
				   	Html::rawElement( 'h2', [], $this->msg( 'extnewpage-help-nsheading' ) )
					. Html::rawElement( 'p', [], $this->msg( 'extnewpage-help-nstext' )->plain() )
				),
			] ) ,
			new \OOUI\PanelLayout( [
				'classes' => [ 'extnewpage-rail-module' ],
				'expanded' => false,
				'padded' => true,
				'framed' => false,
				'content' => new \OOUI\HtmlSnippet( implode( ' ', [
					Html::rawElement( 'h2', [], $this->msg( 'extnewpage-help-contributeheading' ) ),
					$this->msg( 'extnewpage-help-contributetext' )->parse(),
					$hasSearchDigest ? $this->msg( 'extnewpage-help-contributetext-searchdigest' )->parse() : false,
				] ) ),
			] ),
		];
	}

	/**
	 * @return HTMLForm
	 */
	protected function getFormFields() {
		return [
			'title' => [
				'class' => HtmlComplexTitleField::class,
				'creatable' => true,
				'required' => true,
            ]
		];
	}

	/**
	 * Override the submit button's message
	 * @param HTMLForm $form
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'extnewpage-field-title' );
		$form->setSubmitTextMsg( $this->msg( 'create' ) );
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

    public function onSubmit( array $data ) {
		$title = Title::makeTitleSafe( $data['title']['ns'], $data['title']['text'] );
		$url = $title->getFullUrlForRedirect( [
			'action' => 'edit',
		] );
		$this->getOutput()->redirect( $url );
    }

	protected function getGroupName() {
		return 'pagetools';
	}
}
