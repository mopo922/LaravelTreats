<?php

namespace LaravelTreats\Controller\Api;

use Auth;
use LaravelTreats\Controller\Controller as BaseController;
use LaravelTreats\Pattern\Repository\Repository;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    /**
     * Should we inject a user_id into the input for "update" request?
     *
     * @var boolean
     */
    protected $injectUserId = false;

    /**
     * Repository data layer. Should be injected in the child class's constructor.
     *
     * @var App\Pattern\Repository\Repository $repository
     */
    protected $repository;

    /**
     * General setup for the whole controller.
     *
     * @return mixed
     */
    protected function general()
    {
        if (!$this->checkPermissions()) {
            abort(403);
        }
    }

    /**
     * Authorize the current User for this action.
     *
     * @return bool
     */
    protected function checkPermissions()
    {
        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if ($this->injectUserId) {
            $data['user_id'] = Auth::user()->id;
        }

        return $this->repository->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->repository->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
