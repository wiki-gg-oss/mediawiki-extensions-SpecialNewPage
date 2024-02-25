<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use FormSpecialPage;
use Title;

final class SpecialNewPage extends FormSpecialPage {
	public function __construct() {
		parent::__construct( 'NewPage', 'edit' );
	}

	/**
	 * @return HTMLForm
	 */
	protected function getFormFields() {
		return [
			'title' => [
				'class' => HtmlComplexTitleField::class,
				'label-message' => 'newpage-field-title',
				'creatable' => true,
				'required' => true,
            ]
		];
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
