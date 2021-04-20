<?php

namespace App\Http\Controllers;

use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Models\Card;
use App\Services\Card\CardService;
use App\Services\FieldValidationService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response($request->user()->cards()->with('fields')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCardRequest $request
     * @param FieldValidationService $service
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCardRequest $request, FieldValidationService $service)
    {
        $validated = $request->validated();
        $validatedFields = $service->validateFields($validated['fields']);

        if (!empty($validatedFields)) {
            return response([
                'errors' => $validatedFields
            ], 422);
        }

        $request->user()
            ->cards()
            ->create([
                'title' => $validated['title'],
                'username' => $validated['username'],
                'description' => $validated['description']
            ])
            ->fields()
            ->createMany($validated['fields']);

        return response(null);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Card $card)
    {
        if (!$request->user()->hasCard($card)) {
            return response(null, 403);
        }

        return response($card->withFields());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCardRequest $request
     * @param \App\Models\Card $card
     * @param FieldValidationService $service
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCardRequest $request, Card $card, FieldValidationService $service)
    {
        if (!$request->user()->hasCard($card)) {
            return response(null, 403);
        }

        $validated = $request->validated();

        $card->update([
            'title' => $validated['title'],
            'username' => $validated['username'],
            'description' => $validated['description']
        ]);

        $validatedFields = $service->validateFields($validated['fields']);

        if ($validatedFields->isNotEmpty()) {
            return response($validatedFields->messages(), 422);
        }

        $fields = $validated['fields'];
        foreach ($fields as $field) {
            $card->fields()->updateOrCreate(
                [
                    'id' => $field['id'] ?? null
                ],
                $field
            );
        }

        return response(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Card $card)
    {
        if (!$request->user()->hasCard($card)) {
            return response(null, 403);
        }

        return response($card->delete());
    }
}
