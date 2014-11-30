<?php

class DefaultController extends Website_Controller_Action {
	
	public function defaultAction () {
        $this->enableLayout();

        $this->setLayout("fresh-crm.local");

	}
}
