<?php

namespace LaravelTreats\Controller;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Request;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var Illuminate\View\View $layout The view object for rendering. */
    protected $layout;

    /** @var array $modules The general modules available on the site. */
    protected $modules = [];

    /** @var array $viewless Actions with no view script. */
    protected $viewless = [];

    /** @var string $action The current controller action. */
    protected $action = '';

    /** @var string $controller The current controller. */
    protected $controller = '';

    /** @var string $controllerNamespace The default controller namespace. */
    protected $controllerNamespace = 'App\Http\Controllers\\';

    /** @var string $viewScript Allows child classes to override the standard view script mapping. */
    protected $viewScript;

    /**
     * Extends parent::callAction()
     *
     * Trigger misc global setup.
     *
     * Any controller can define a general() method
     * for setup common to all actions.
     *
     * @param string $method
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $result = null;
        $this->setupLayout($method);

        // Call the action if $this->general() doesn't suggest otherwise
        if (method_exists($this, 'general'))
            $result = $this->general();
        if (!$result)
            $result = parent::callAction($method, $parameters);

        return isset($result) ? $result : $this->layout; // must use isset() for case of empty array in AJAX response
    }

    /**
     * Setup the layout used by the controller.
     *
     * @param string $method
     */
    protected function setupLayout(string $method)
    {
        // Get Controller & Action names
        $delimeter = '-';
        $this->controller = str_replace(
            // Laravel snake_case() doesn't recognize \, so remove it
            '\\' . $delimeter,
            '.',
            snake_case(
                // Remove fluff from classname
                str_replace([$this->controllerNamespace, 'Controller'], '', get_class($this)),
                $delimeter
            )
        );
        $this->action = strtolower(snake_case(
            substr(
                $method,
                // Don't chop up method for API
                strpos($this->controller, 'api') === 0
                    ? 0
                    : strlen(Request::method())
            ),
            '-'
        ));

        // Create default view mapping
        if ($this->usesDefaultViewMapping()) {
            $this->setViewScript();

            $this->layout = View::make($this->viewScript, [
                'guestHome' => ($this->controllerNamespace . 'IndexController' === get_class($this) && 'getIndex' == $method),
                'module' => $this->controller,
                'action' => $this->action,
            ]);

            $this->layout->modules = $this->modules;
        }
    }

    /** Set the view script. Used by child classes to perform custom view mapping. */
    protected function setViewScript()
    {
        if (!$this->viewScript)
            $this->viewScript = $this->controller . '.' . $this->action;
    }

    /** @return bool Should this Controller use the default view mapping? */
    protected function usesDefaultViewMapping()
    {
        return Request::isMethod('get')
            // && !Request::ajax()
            && 0 !== strpos($this->controller, 'auth.')
            && !in_array($this->action, $this->viewless)
            && strpos($this->controller, 'api') !== 0;
    }
}
