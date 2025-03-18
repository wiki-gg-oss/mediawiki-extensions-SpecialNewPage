<?php
namespace MediaWiki\Extension\NewPage\SpecialPages;

use MediaWiki\HTMLForm\HTMLForm;

final class SpecialNewRedirect extends SpecialNewPageBase {
	public function __construct() {
		parent::__construct( 'NewRedirect', 'edit' );
	}

	protected function getJsModuleName(): string {
		return 'ext.newredirect';
	}

	/**
	 * @return \OOUI\PanelLayout[]
	 */
	protected function getHelpRailModules(): array {
		return [];
	}

	protected function getFormFields() {
		$request = $this->getRequest();
		return [
			'nrfrom' => [
				'label-message' => 'extnewpage-field-redirect-from',
				'class' => HtmlComplexTitleField::class,
				'creatable' => true,
				'required' => true,
				'default' => $request->getText( 'nrfrom' ),
            ],
			'nrto' => [
				'label-message' => 'extnewpage-field-redirect-to',
				'class' => HtmlComplexTitleField::class,
				'creatable' => true,
				'required' => true,
				'default' => $request->getText( 'nrto' ),
            ],
		];
	}

	/**
	 * Override the submit button's message
	 * @param HTMLForm $form
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'extnewpage-field-create-redirect' );
		$form->setSubmitTextMsg( $this->msg( 'create' ) );
	}

    public function onSubmit( array $data ) {
    }
}
