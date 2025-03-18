<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\SpecialPage\FormSpecialPage;

abstract class SpecialNewPageBase extends FormSpecialPage {
	/**
	 * Add header elements like block log entries, etc.
	 * @return string
	 */
	protected function preHtml() {
		$this->getOutput()->addModules( [ $this->getJsModuleName() ] );
		$this->getOutput()->addModuleStyles( [ 'ext.newpage.styles' ] );

		return parent::preHtml()
			// This container will be closed in postHtml
			. Html::openElement( 'div', [ 'class' => [ 'extnewpage-form-wrapper' ] ] );
	}

	abstract protected function getJsModuleName(): string;

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
	protected function getHelpRailModules(): array {
		return [];
	}

	/**
	 * @return array
	 */
	abstract protected function getFormFields();

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

	protected function getGroupName() {
		return 'pagetools';
	}
}
