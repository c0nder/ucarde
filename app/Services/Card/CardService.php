<?php


namespace App\Services\Card;


use App\Models\Field;
use App\Services\AbstractResourceService;
use App\Services\FieldValidationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        $fields = $data['fields'];
        $modelFields = $model->fields->pluck('id');

        foreach ($modelFields as $modelField) {
            if (!in_array($modelField, array_column((array)$fields, 'id'))) {
                Field::find($modelField)->delete();
            }
        }

        $fieldService = App::make(FieldValidationService::class);

        $fieldService->validateFields($data['fields']);

        $model->update([
            'title' => $data['title'],
            'username' => $data['username'],
            'description' => $data['description']
        ]);

//        $deletedFields = array_intersect($modelFields, array_column((array)$fields, 'id'));
//
//        if ($deletedFields) {
//            foreach ($deletedFields as $fieldId) {
//                Field::find($fieldId)->delete();
//            }
//        }

        foreach ($fields as $field) {
            $model->fields()->updateOrCreate(
                [
                    'id' => $field['id'] ?? null
                ],
                $field
            );
        }

        return response(null);
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
