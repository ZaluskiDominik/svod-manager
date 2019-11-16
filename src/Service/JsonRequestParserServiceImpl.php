<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class JsonRequestParserServiceImpl implements JsonRequestParserService
{
    public function parse(Request $request): array
    {
        $bodyWithoutNewLines = preg_replace('/\n/', '', $request->getContent());

        return json_decode($bodyWithoutNewLines, true);
    }
}
