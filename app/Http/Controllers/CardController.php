<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureCardBelongsToUser;
use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Models\Card;
use App\Services\Card\CardService;
use App\Services\FieldValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Array_;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureCardBelongsToUser::class, [
            'except' => [
                'index',
                'store',
                'showByPublicId',
                'massUpdate'
            ]
        ]);
    }

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

        $service->validateFields($validated['fields']);

        $request->user()
            ->cards()
            ->create([
                'title' => $validated['title'],
                'username' => $validated['username'],
                'description' => $validated['description'],
                'public_id' => Str::random(20)
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
    public function show(Card $card)
    {
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
        $validated = $request->validated();

        $service->validateFields($validated['fields']);

        $card->update([
            'title' => $validated['title'],
            'username' => $validated['username'],
            'description' => $validated['description']
        ]);

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
    public function destroy(Card $card)
    {
        return response($card->delete());
    }

    public function showByPublicId(string $publicId)
    {
        $card = Card::where('public_id', $publicId)->first();

        if ($card === null) {
            return response(null, 404);
        }

        return response($card->withFields());
    }

    public function massUpdate(CardService $cardService)
    {
        $data = request()->all();

        foreach ($data as $card) {
            $cardModel = Card::find($card['id']);
            $cardService->update($cardModel, $card);
        }
    }
}
