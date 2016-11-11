<?php

namespace LaravelTreats\Controller;

class TermsController extends Controller
{
    /**
     * Setup the layout used by the controller.
     *
     * @param string $strMethod
     */
    protected function setupLayout(string $strMethod)
    {
        $this->strViewScript = 'LaravelTreats::terms.' . $this->strAction;
        parent::setupLayout($strMethod);
    }

    /**  */
    public function general()
    {
        $this->layout->domain = config('LaravelTreats::layout.site.domain');
        $this->layout->siteName = config('LaravelTreats::layout.site.name');
    }

    /** Terms of Use */
    public function getIndex() {}

    /** Privacy Policy */
    public function getPrivacy() {}
}
