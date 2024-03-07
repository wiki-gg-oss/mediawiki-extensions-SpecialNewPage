<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use FormSpecialPage;
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
		return parent::preHtml();
	}

	/**
	 * @return HTMLForm
	 */
	protected function getFormFields() {
		return [
			'title' => [
				'class' => HtmlComplexTitleField::class,
				'label-message' => 'extnewpage-field-title',
				'creatable' => true,
				'required' => true,
            ]
		];
	}

	/**
	 * Override the submit button's message
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$result = parent::getForm();
		$result->setSubmitTextMsg( $this->msg( 'create' ) );
		return $result;
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
