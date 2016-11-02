<?php

namespace LaravelTraffic\Api;

use LaravelTraffic\Controller as BaseController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;

class Controller extends BaseController
{
    /** @var array $aErrors A pre-validation error description. */
    protected $aErrors;

    /** @var array $aInput The user input. */
    protected $aInput = [];

    /** @var bool $bInjectUserId Should we inject a user_id into the input for every request? */
    protected $bInjectUserId = false;

    /** @var bool $bReturnsArray Does the current action return a data array? */
    protected $bReturnsArray = true;

    /** @var string $strMethod The method being called. */
    protected $strMethod = '';

    /** @var string $strModel The fully-qualified model class name. */
    protected $strModel = '';

    /** @var string $strModelNamespace The default model namespace. */
    protected $strModelNamespace = '\App\Model\\';

    /** @var string $strRedirect Optional custom redirect URL. */
    protected $strRedirect = '';

    /** @var string $strResourceType The type of resource we're dealing with (User, Campus, etc.) */
    protected $strResourceType = '';

    /** @var string $strRoutePrefix The route name prefix used for the API. */
    protected $strRoutePrefix = 'api.';

    /**
     * Extends parent::callAction()
     *
     * Format API responses - JSON for AJAX requests,
     * redirect back w/ data for normal requests.
     *
     * @param string $strMethod
     * @param array $aParameters
     * @return string|Illuminate\Http\RedirectResponse
     */
    public function callAction($strMethod, $aParameters)
    {
        $oResponse = null;
        $this->strMethod = $strMethod;

        $mResult = parent::callAction($strMethod, $aParameters);

        if ($this->bReturnsArray) {
            if (!is_array($mResult))
                abort(500, 'Bad API response data.');

            // Return JSON for AJAX requests
            if (request()->ajax()) {
                $oResponse = response()->json($mResult);

            // Return redirect-back and flash data for normal requests.
            } else {
                $oResponse = empty($this->strRedirect) ? back() : redirect($this->strRedirect);
                foreach ($mResult as $strKey => $mValue)
                    $oResponse->with($strKey, $mValue);
                if ('store' !== $strMethod || isset($mResult['error']))
                    $oResponse->withInput();
            }
        } else {
            $oResponse = $mResult;
        }

        return $oResponse;
    }

    /** @return mixed General setup for the whole controller. */
    protected function general()
    {
        $this->strResourceType = studly_case(str_replace(
            $this->strRoutePrefix, '', $this->strController
        ));
        $this->strModel = $this->strModelNamespace . $this->strResourceType;
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
        $strClass = $this->strModel;

        return $strClass::destroy($id)
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
        $strClass = $this->strModel;
        $oQuery = $strClass::query();

        foreach ($this->aInput as $strKey => $mValue) {
            $strWhere = is_array($mValue) ? 'whereIn' : 'where';
            $oQuery->$strWhere($strKey, $mValue);
        }

        return $oQuery->first();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $strClass = $this->strModel;
        return $strClass::find($id)->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return array
     */
    public function store()
    {
        $strClass = $this->strModel;

        $this->validate(request(), $this->getValidationRules());

        // Create
        $oModel = new $strClass($this->aInput);
        return $oModel->save()
            ? ['success' => ucfirst($this->readableResourceType()) . ' created successfully.']
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
        $strClass = $this->strModel;

        return $strClass::$rules;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return array
     */
    public function update($id)
    {
        $aReturn = [];
        $strClass = $this->strModel;

        $this->aInput['id'] = $id;
        $this->validate(request(), $this->getValidationRules());

        $oModel = $strClass::find($id);
        if ($this->canEdit($oModel)) {
            $this->fillModel($oModel);

            $aReturn = $oModel->push()
                ? ['success' => ucfirst($this->readableResourceType()) . ' updated successfully.']
                : ['error' => 'There was a problem saving the ' . $this->readableResourceType() . '.'];
        } else {
            $aReturn = [
                'error' => 'You don\'t have permission to edit this '
                    . $this->readableResourceType() . '.'
            ];
        }

        return $aReturn;
    }

    /** @return bool Check if the current User can edit the given model. */
    protected function canEdit(Model $oModel)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        $mResponse = parent::buildFailedValidationResponse($request, $errors);
        if (!$mResponse instanceof \Illuminate\Http\JsonResponse)
            $mResponse->with('error', 'One or more fields is incorrect.');
        return $mResponse;
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
     * @param Illuminate\Database\Eloquent\Model $oModel
     */
    protected function fillModel(Model $oModel)
    {
        $oModel->fill($this->aInput);
    }

    /** Retrieve, sanitize, and update the user input. */
    protected function processInput()
    {
        if (empty($this->aInput)) {
            $this->aInput = request()->except('_token');

            if ($this->bInjectUserId)
                $this->aInput['user_id'] = Auth::user()->id;
        }
        $this->sanitize();
        request()->replace($this->aInput); // @todo IS THIS NECESSARY?
    }

    /** @return string Human-readable version of the resource type. */
    protected function readableResourceType()
    {
        // @TODO EXPLAIN THIS IN THE README
        return str_replace('_', ' ', snake_case($this->strResourceType));
    }

    /** Sanitize the input - overridden in child class. */
    protected function sanitize() {}

    /**
     * Validate the given request with the given rules.
     *
     * @param \Illuminate\Http\Request $oRequest
     * @param array $aRules
     * @param array $aMessages
     * @param array $aCustomAttributes
     */
    public function validate(Request $oRequest, array $aRules, array $aMessages = [], array $aCustomAttributes = [])
    {
        // Check for previous errors
        if ($this->hasPreValidationErrors()) {
            $oValidator = Validator::make([], []);
            foreach ($this->aErrors as $strField => $strError)
                $oValidator->errors()->add($strField, $strError);
            $this->throwValidationException($oRequest, $oValidator);
        }

        if ($this->bInjectUserId && 'index' !== $this->strMethod)
            $aRules['user_id'] = 'required|in:' . Auth::id();

        parent::validate($oRequest, $aRules, $aMessages, $aCustomAttributes);
    }

    /**
     * Add a pre-validation error message.
     *
     * @param string $strField Field name.
     * @param string $strMessage Error message.
     */
    protected function addPreValidationError(string $strField, string $strMessage)
    {
        $this->aErrors[$strField] = $strMessage;
    }

    /** @return bool Check if we have any pre-validation errors. */
    protected function hasPreValidationErrors()
    {
        return !empty($this->aErrors);
    }
}
