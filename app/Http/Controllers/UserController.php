<?php

namespace AppHttpControllers;

use AppModelsUser;
use AppModelsRole;
use AppHttpRequestsStoreUserRequest;
use AppHttpRequestsUpdateUserRequest;
use AppServicesUserService;
use AppHttpMiddlewareCheckPermission;
use IlluminateRoutingControllersHasMiddleware;
use IlluminateRoutingControllersMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':users.view', only: ['index', 'show', 'roles']),
            new Middleware(CheckPermission::class . ':users.create', only: ['store']),
            new Middleware(CheckPermission::class . ':users.edit', only: ['update']),
            new Middleware(CheckPermission::class . ':users.delete', only: ['destroy']),
        ];
    }

    public function index(UserService $userService)
    {
        $users = $userService->getAllUsers();
        return response()->json($users);
    }

    public function store(StoreUserRequest $request, UserService $userService)
    {
        $user = $userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    public function show(User $user, UserService $userService)
    {
        return response()->json($userService->getUser($user));
    }

    public function update(UpdateUserRequest $request, User $user, UserService $userService)
    {
        $updatedUser = $userService->updateUser($user, $request->validated());
        return response()->json($updatedUser);
    }

    public function destroy(User $user, UserService $userService)
    {
        $userService->deleteUser($user);
        return response()->json(null, 204);
    }

    public function roles()
    {
        return response()->json(Role::all());
    }
}

