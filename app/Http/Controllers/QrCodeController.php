<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureCardBelongsToUser;
use App\Models\Card;
use App\Models\Field;
use App\Services\QrCode\QrCodeService;

class QrCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureCardBelongsToUser::class);
    }

    public function generateByCard(Card $card, QrCodeService $qrCodeService)
    {
        return response($qrCodeService->generateByCard($card));
    }

    public function generateByField(Field $field, QrCodeService $qrCodeService)
    {
        $qrCode = $qrCodeService->generateByField($field);

        if ($qrCode === null) {
            return response(null, 404);
        }

        return response($qrCode);
    }
}
