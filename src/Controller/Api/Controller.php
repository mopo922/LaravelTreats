<?php

namespace LaravelTreats\Controller\Api;

use Auth;
use Validator;
use LaravelTreats\Controller\Controller as BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    /** @var array $errors A pre-validation error description. */
    protected $errors = [];

    /** @var array $input The user input. */
    protected $input = [];

    /** @var bool $injectUserId Should we inject a user_id into the input for every request? */
    protected $injectUserId = false;

    /** @var bool $returnsArray Does the current action return a data array? */
    protected $returnsArray = true;

    /** @var string $method The method being called. */
    protected $method = '';

    /** @var string $model The fully-qualified model class name. */
    protected $model = '';

    /** @var string $modelNamespace The default model namespace. */
    protected $modelNamespace = '\App\Model\\';

    /** @var string $redirectUrl Optional custom redirect URL. */
    protected $redirectUrl = '';

    /** @var string $resourceType The type of resource we're dealing with (User, Campus, etc.) */
    protected $resourceType = '';

    /** @var string $routePrefix The route name prefix used for the API. */
    protected $routePrefix = 'api.';

    /**
     * Extends parent::callAction()
     *
     * Format API responses - JSON for AJAX requests,
     * redirect back w/ data for normal requests.
     *
     * @param string $method
     * @param array $parameters
     * @return string|Illuminate\Http\RedirectResponse
     */
    public function callAction($method, $parameters)
    {
        $response = null;
        $this->method = $method;

        $result = parent::callAction($method, $parameters);

        if ($this->returnsArray) {
            if (!is_array($result)) {
                abort(500, 'Bad API response data.');
            }

            // Return JSON for AJAX and GET requests
            if (request()->ajax() || 'index' === $method || 'show' === $method) {
                $response = response()->json($result);

            // Return redirect-back and flash data for normal requests.
            } else {
                $response = empty($this->redirectUrl) ? back() : redirect($this->redirectUrl);
                foreach ($result as $key => $value) {
                    $response->with($key, $value);
                }
                if ('store' !== $method || isset($result['error'])) {
                    $response->withInput();
                }
            }
        } else {
            $response = $result;
        }

        return $response;
    }

    /** @return mixed General setup for the whole controller. */
    protected function general()
    {
        $this->resourceType = studly_case(str_replace(
            $this->routePrefix, '', $this->controller
        ));
        if (!$this->model)
            $this->model = $this->modelNamespace . $this->resourceType;
        $this->processInput();

        if (!$this->checkPermissions())
            abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $class = $this->model;

        return $class::destroy($id)
            ? ['success' => ucfirst($this->readableResourceType()) . ' removed successfully.']
            : ['error' => 'There was a problem removing the ' . $this->readableResourceType() . '.'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        return $this->findModel()->toArray();
    }

    /**
     * Use the current input to find a record.
     *
     * Apps can override this with their custom methods of finding based on criteria.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function findModel()
    {
        $class = $this->model;
        $query = $class::query();

        foreach ($this->input as $key => $value) {
            $where = is_array($value) ? 'whereIn' : 'where';
            $query->$where($key, $value);
        }

        return $query->get();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class = $this->model;
        return $class::find($id)->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return array
     */
    public function store()
    {
        $class = $this->model;

        $this->validate(request(), $this->getValidationRules());

        // Create
        $model = new $class($this->input);
        return $model->save()
            ? [
                'success' => ucfirst($this->readableResourceType()) . ' created successfully.',
                'model' => $model->toArray(),
            ]
            : ['error' => 'There was a problem saving the ' . $this->readableResourceType() . '.'];
    }

    /**
     * Get validation rules for the current model.
     *
     * Apps can override this with their custom validation rules.
     *
     * @return array
     */
    protected function getValidationRules()
    {
        $class = $this->model;

        return $class::$rules;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return array
     */
    public function update($id)
    {
        $return = [];
        $class = $this->model;

        $this->input['id'] = $id;
        $this->validate(request(), $this->getValidationRules());

        $model = $class::find($id);
        if ($this->canEdit($model)) {
            $this->fillModel($model);

            $return = $model->push()
                ? ['success' => ucfirst($this->readableResourceType()) . ' updated successfully.']
                : ['error' => 'There was a problem saving the ' . $this->readableResourceType() . '.'];
        } else {
            $return = [
                'error' => 'You don\'t have permission to edit this '
                    . $this->readableResourceType() . '.'
            ];
        }

        return $return;
    }

    /**
     * Check if the current User can edit the given model.
     *
     * @param Model $model The model we want to edit.
     * @return bool
     */
    protected function canEdit(Model $model)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        $response = parent::buildFailedValidationResponse($request, $errors);
        if (!$response instanceof \Illuminate\Http\JsonResponse) {
            $response->with('error', 'One or more fields is incorrect.');
        }
        return $response;
    }

    /** @return bool Authorize the current User for this action. */
    protected function checkPermissions()
    {
        return true;
    }

    /**
     * Fill the model with the input.
     *
     * Controllers can override this with custom fill logic.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     */
    protected function fillModel(Model $model)
    {
        $model->fill($this->input);
    }

    /** Retrieve, sanitize, and update the user input. */
    protected function processInput()
    {
        if (empty($this->input)) {
            $this->input = request()->except('_token');

            if ($this->injectUserId) {
                $this->input['user_id'] = Auth::user()->id;
            }
        }
        $this->sanitize();
        request()->replace($this->input); // @todo IS THIS NECESSARY?
    }

    /** @return string Human-readable version of the resource type. */
    protected function readableResourceType()
    {
        // @TODO EXPLAIN THIS IN THE README
        return str_replace('_', ' ', snake_case($this->resourceType));
    }

    /** Sanitize the input - overridden in child class. */
    protected function sanitize() {}

    /**
     * Validate the given request with the given rules.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        // Check for previous errors
        if ($this->hasPreValidationErrors()) {
            $validator = Validator::make([], []);
            foreach ($this->errors as $field => $error) {
                $validator->errors()->add($field, $error);
            }
            $this->throwValidationException($request, $validator);
        }

        if ($this->injectUserId && 'index' !== $this->method) {
            $rules['user_id'] = 'required|in:' . Auth::id();
        }

        parent::validate($request, $rules, $messages, $customAttributes);
    }

    /**
     * Add a pre-validation error message.
     *
     * @param string $field Field name.
     * @param string $message Error message.
     */
    protected function addPreValidationError(string $field, string $message)
    {
        $this->errors[$field] = $message;
    }

    /** @return bool Check if we have any pre-validation errors. */
    protected function hasPreValidationErrors()
    {
        return !empty($this->errors);
    }
}
