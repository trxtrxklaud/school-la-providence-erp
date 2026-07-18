<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Http\Middleware\CheckPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Laravel 11: static middleware() replaces $this->middleware() in constructor.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':users.view',   only: ['index', 'show']),
            new Middleware(CheckPermission::class . ':users.create', only: ['store']),
            new Middleware(CheckPermission::class . ':users.edit',   only: ['update']),
            new Middleware(CheckPermission::class . ':users.delete', only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->userService->getAllUsers()
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(
            $this->userService->getUser($user)
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());
        return response()->json($updatedUser);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);
        return response()->json(null, 204);
    }

    public function roles(): JsonResponse
    {
        return response()->json(Role::all());
    }
}
