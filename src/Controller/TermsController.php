<?php

namespace LaravelTreats\Controller;

class TermsController extends Controller
{
    /** Set the view script. Used by child classes to perform custom view mapping. */
    protected function setViewScript()
    {
        $this->viewScript = 'LaravelTreats::terms.' . $this->action;
    }

    /**  */
    public function general()
    {
        $this->layout->domain = trans('LaravelTreats::layout.site.domain');
        $this->layout->siteName = trans('LaravelTreats::layout.site.name');
    }

    /** Terms of Use */
    public function getIndex() {}

    /** Privacy Policy */
    public function getPrivacy() {}
}
