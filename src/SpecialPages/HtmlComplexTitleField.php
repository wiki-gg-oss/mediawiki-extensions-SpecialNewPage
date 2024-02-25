<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use HTMLTextField;
use MalformedTitleException;
use MediaWiki\Widget\ComplexTitleInputWidget;
use Title;
use WebRequest;

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
		$text = $title->getPrefixedText();

		if ( $this->mParams['creatable'] && !$title->canExist() ) {
			return $this->msg( 'htmlform-title-not-creatable', $text );
		}

		return true;
	}

	protected function getInputWidget( $params ) {
		$params['namespace'] = [
			'name' => $this->mName . '-ns',
		];
		$params['title'] = [
			'name' => $this->mName . '-text',
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
