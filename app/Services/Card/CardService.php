<?php


namespace App\Services\Card;


use App\Services\AbstractResourceService;
use App\Services\FieldValidationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CardService implements AbstractResourceService
{
    private $fieldValidationService;
    private $request;

    public function __construct(Request $request, FieldValidationService $fieldValidationService)
    {
        $this->fieldValidationService = $fieldValidationService;
        $this->request = $request;
    }

    public function store(array $data)
    {
        $validatedFields = $this->fieldValidationService->validateFields($data['fields']);

        if ($validatedFields->isNotEmpty()) {
            return response($validatedFields->messages(), 422);
        }

        $this->request
            ->user()
            ->cards()
            ->create([
                'title' => $data['title'],
                'username' => $data['username'],
                'description' => $data['description']
            ])
            ->fields()
            ->createMany($data['fields']);

        return true;
    }

    public function update(Model $model, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(Model $model)
    {
        // TODO: Implement delete() method.
    }

    public function show(Model $model): Model
    {
        // TODO: Implement show() method.
    }

}
