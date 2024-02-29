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

- [ ]  Criar autenticação para login do usuário
- [ ]  Só permitir o get e o patch se o usuario esta logado