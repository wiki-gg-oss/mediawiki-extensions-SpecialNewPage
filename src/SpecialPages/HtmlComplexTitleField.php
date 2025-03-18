<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use MediaWiki\HTMLForm\Field\HTMLTextField;
use MediaWiki\Title\Title;
use MediaWiki\Widget\ComplexTitleInputWidget;

/**
 * ComplexTitleInputWidget wrapper for HTMLForm.
 *
 * Optional parameters:
 * 'creatable' - Whether to validate the title is creatable (not a special page)
 */
class HtmlComplexTitleField extends HTMLTextField {
	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$params += [
			'creatable' => false,
			// This overrides the default from HTMLFormField
			'required' => true,
		];

		parent::__construct( $params );
	}

	/**
	 * @return ?array
	 */
	public function getDefault() {
		if ( is_string( $this->mDefault ) && !empty( $this->mDefault ) ) {
			$title = Title::newFromText( $this->mDefault );
			return [
				'ns' => $title->getNamespace(),
				'text' => $title->getText(),
			];
		}
		return null;
	}

	public function validate( $value, $alldata ) {
		// Default value (from getDefault()) is null
		if ( $value === null || empty( $value ) ) {
			$value = [
				'ns' => 0,
				'text' => '',
			];
		}

		$textValidation = parent::validate( $value['text'], $alldata );
		if ( $textValidation !== true ) {
			return $textValidation;
		}

		$title = Title::makeTitleSafe( $value['ns'], $value['text'] );
		if ( !$title ) {
			return $this->msg( 'title-invalid' );
		}

		if ( $this->mParams['creatable'] && !$title->canExist() ) {
			return $this->msg( 'htmlform-title-not-creatable', $title->getPrefixedText() );
		}

		return true;
	}

	protected function getInputWidget( $params ) {
		$params['namespace'] = [
			'name' => $this->mName . '-ns',
			'value' => ( $params['value'] ?? [] )[ 'ns' ] ?? 0,
		];
		$params['title'] = [
			'name' => $this->mName . '-text',
			'validateTitle' => true,
			'value' => ( $params['value'] ?? [] )[ 'text' ] ?? '',
		];
		if ( $this->mParams['creatable'] ) {
			$params['title']['suggestions'] = false;
		}
		return new ComplexTitleInputWidget( $params );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets' ];
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return ?array
	 */
	public function loadDataFromRequest( $request ) {
		$nsName = $this->mName . '-ns';
		$titleName = $this->mName . '-text';
		if ( $request->getCheck( $titleName ) ) {
			return [
				'ns' => $request->getInt( $nsName, 0 ),
				'text' => $request->getText( $titleName ),
			];
		} else {
			return $this->getDefault();
		}
	}
}
