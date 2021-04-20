<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Field;
use App\Services\QrCode\QrCodeService;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }


    public function generateByCard(Request $request, Card $card)
    {
        if (!$request->user()->hasCard($card)) {
            return response(null, 403);
        }

        return response($this->qrCodeService->generateByCard($card));
    }

    public function generateByField(Request $request, Field $field)
    {
        if (!$request->user()->hasField($field)) {
            return response(null, 403);
        }

        $qrCode = $this->qrCodeService->generateByField($field);

        if ($qrCode === null) {
            return response(null, 404);
        }

        return response($qrCode);
    }
}
