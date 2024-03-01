# Simples modelo de api em laravel

- [x]  Criar rotas REST

```php
Route::get("/user",[UserController::class,"index"])->name("users.index");
Route::get("/user/{id}",[UserController::class,"show"])->name("users.show");
Route::post("/user",[UserController::class,"store"])->name("users.store");
Route::patch("/user/{id}",[UserController::class,"update"])->name("users.update");
Route::delete("/user/{id}",[UserController::class,"destroy"])->name("users.destroy");
```

- [x]  Criar Rules (regras) centralizadas

```php
<?php

namespace App\Rules;

class UserValidationRules
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }
}

```

- [x]  Se o usuário está editando, não entrar erro de email já existente

```php
class StoreUserRequest extends FormRequest
{
...
public function rules(): array
    {
        $rules = UserValidationRules::rules();
        $rules["email"] = $rules["email"] . "|unique:users";
        return $rules;
    }
```

- [x]  Criar autenticação para login do usuário

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // /**
    //  * Get the authenticated User.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function me()
    // {
    //     return response()->json(auth()->user());
    // }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
```

- [x]  Só permitir o get e o patch se o usuario esta logado

```php
Route::group(["middleware" => ["jwt.auth"]],function () {
    Route::get("/user",[UserController::class,"index"])->name("users.index");
    Route::get("/user/{id}",[UserController::class,"show"])->name("users.show");
    Route::post("/user",[UserController::class,"store"])->name("users.store");
    Route::patch("/user/{id}",[UserController::class,"update"])->name("users.update");
    Route::delete("/user/{id}",[UserController::class,"destroy"])->name("users.destroy");

    Route::post("/auth/logout",[AuthController::class,"logout"])->name("auth.logout");
    Route::post("/auth/refresh",[AuthController::class,"refresh"])->name("auth.refresh");
});
```

