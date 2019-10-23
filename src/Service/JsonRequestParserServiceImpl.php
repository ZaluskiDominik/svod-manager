<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class JsonRequestParserServiceImpl implements JsonRequestParserService
{
    public function parse(Request $request): array
    {
        $bodyWithoutWhiteSpaces = preg_replace('/\s/', '', $request->getContent());

        return json_decode($bodyWithoutWhiteSpaces, true);
    }
}
